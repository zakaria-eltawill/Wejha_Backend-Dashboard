#!/usr/bin/env bash
set -euo pipefail

# CONTAINER_ROLE distinguishes the app/queue/scheduler services, which all share this
# same image — only the "app" instance performs one-time setup (migrations, caching)
# so multiple containers starting together don't race on the same migration.
ROLE="${CONTAINER_ROLE:-app}"

wait_for_postgres() {
    echo "Waiting for PostgreSQL at ${DB_HOST:-db}:${DB_PORT:-5432}..."
    local attempts=0
    until pg_isready -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-wejha}" > /dev/null 2>&1; do
        attempts=$((attempts + 1))
        if [ "$attempts" -ge 60 ]; then
            echo "PostgreSQL did not become ready in time." >&2
            exit 1
        fi
        sleep 2
    done
    echo "PostgreSQL is up."
}

if [ "$ROLE" = "app" ]; then
    wait_for_postgres

    php artisan package:discover --ansi
    php artisan filament:upgrade

    php artisan migrate --force

    # Idempotent (firstOrCreate) — safe on every restart, never touches an existing
    # admin account's password once it exists. Only actually creates anything on a
    # fresh database (roles + the default admin@wejha.com account).
    php artisan db:seed --force

    if [ ! -L public/storage ]; then
        php artisan storage:link
    fi

    if [ "${APP_ENV:-production}" = "production" ]; then
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan event:cache
    fi

    echo "App container ready."
elif [ "$ROLE" = "queue" ] || [ "$ROLE" = "scheduler" ]; then
    wait_for_postgres
    echo "${ROLE} container ready."
fi

exec "$@"
