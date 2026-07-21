# Docker deployment

Production-ready containerized deployment of the same codebase you develop in.
There is no separate "Docker version" of the app — these files just package the
existing project. Any change made to the app (a migration, a new route, an edited
Blade file) is automatically picked up the next time the image is rebuilt; nothing
here needs to be hand-mirrored.

## Architecture

| Service | What it runs | Notes |
|---|---|---|
| `app` | Nginx + PHP-FPM (via supervisord, one container) | Serves HTTP on `:8080` internally, published on host `:${APP_PORT:-80}` |
| `queue` | `php artisan queue:work` | Same image as `app`, different command |
| `scheduler` | `php artisan schedule:work` | Runs the 3 scheduled commands in `routes/console.php` (archive past events, send scheduled notifications, daily event reminders) |
| `db` | PostgreSQL 16 | Named volume `postgres_data`, not published to the host by default |

All four share one multi-stage `Dockerfile`:
1. **`vendor`** — Composer install (`--no-dev --optimize-autoloader`)
2. **`frontend`** — `npm ci && npm run build` (Vite assets)
3. **`runtime`** — `php:8.3-fpm-alpine` + Nginx + supervisord, non-root user (`wejha`, uid 1000) throughout. Nginx listens on the unprivileged port 8080 specifically so nothing in the container needs root.

`docker/entrypoint.sh` runs once per container start: waits for Postgres, and — only
on the `app` service (`CONTAINER_ROLE=app`) — runs `package:discover`,
`filament:upgrade`, `php artisan migrate --force`, `storage:link` (if not already
linked), and (in `APP_ENV=production`) config/route/view/event caching. `queue` and
`scheduler` wait for `app`'s healthcheck (not just for Postgres) before starting, so
they never hit a not-yet-migrated table on a fresh deploy.

## First-time deploy

```bash
# 1. Copy the env template and fill in every CHANGE_ME value
cp .env.docker.example .env

# 2. Generate an APP_KEY and paste it into .env (APP_KEY=...)
docker compose run --rm app php artisan key:generate --show

# 3. Place your Firebase service-account JSON where the app expects it
#    (never committed to git, never baked into the image — see .dockerignore)
cp /path/to/your/firebase-service-account.json storage/app/firebase-auth.json

# 4. Build and start everything
docker compose up -d --build
```

That's it — migrations, `storage:link`, and config caching all happen automatically
inside the `app` container on first boot. No manual steps inside the container are
required.

## Day-to-day

```bash
docker compose logs -f app          # app logs (stdout/stderr — see LOG_CHANNEL=stderr)
docker compose logs -f queue
docker compose logs -f scheduler
docker compose exec app php artisan tinker
docker compose ps                   # health status of all services
```

### Deploying a code change

```bash
git pull
docker compose up -d --build        # rebuilds the image, restarts app/queue/scheduler
```

Migrations run automatically on the next `app` container start (via
`docker/entrypoint.sh`) — no separate migrate step needed.

### Backing up the database

```bash
docker compose exec db pg_dump -U "$DB_USERNAME" "$DB_DATABASE" > backup.sql
```

## Persistent data (survives `docker compose down`, not `down -v`)

- `postgres_data` — the database
- `storage_public` — user uploads (avatars, event banners/covers, imports) — the
  `storage/app/public` disk
- `storage_fonts` — dompdf's Arabic font cache (regenerates automatically if lost,
  just kept for a faster cold start)

Never run `docker compose down -v` in production — the `-v` flag deletes these
named volumes, including the database.

## Security notes

- `.env` is never baked into the image (`.dockerignore`) — it's injected purely as
  container environment variables via `env_file:` in `docker-compose.yml`.
- `storage/app/firebase-auth.json` is likewise excluded from the image and bind-
  mounted read-only at deploy time.
- Every process in every container (nginx, php-fpm, supervisord, artisan commands)
  runs as the non-root `wejha` user (uid 1000) — nothing runs as root.
- `OPCACHE` runs with `validate_timestamps=0` (production mode: files are cached
  forever). This means code changes require an image rebuild + restart to take
  effect — that's intentional for an immutable production image, not a bug.
- `docker/php/production.ini` disables `display_errors` and sets
  `session.cookie_secure=1` — put a TLS-terminating reverse proxy (or set
  `APP_PORT` behind one) in front of this in production; the app itself doesn't
  terminate TLS.

## Validated so far

- `docker build --check` (buildx linter): **no warnings**.
- `docker compose config`: full service graph, dependency chain
  (`db` → `app` → `{queue, scheduler}`), volumes, and networking all resolve
  correctly with no errors.
- **Not yet run**: an actual `docker compose up -d --build`. The machine this was
  built on had under 3GB of free disk during development, too tight to safely pull
  the base images (`php:8.3-fpm-alpine`, `node:20-alpine`, `composer:2`) and run
  `npm ci`/`composer install` without risking a failed build mid-way. Run
  `docker compose up -d --build` yourself once on a machine with a few GB of
  headroom (or your deployment server) to do the first real end-to-end build —
  everything has been reviewed and lints clean, but this step hasn't been
  physically executed.
