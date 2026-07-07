<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->logActivity('create', $model);
    }

    public function updated(Model $model): void
    {
        $this->logActivity('update', $model);
    }

    public function deleted(Model $model): void
    {
        $this->logActivity('delete', $model);
    }

    protected function logActivity(string $action, Model $model): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        if ($action === 'update') {
            $dirty = $model->getDirty();
            foreach ($dirty as $key => $value) {
                // Skip tracking timestamps or passwords changes detailed content for security
                if (in_array($key, ['updated_at', 'password'])) {
                    continue;
                }
                $oldValues[$key] = $model->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (empty($newValues)) {
                return;
            }
        } elseif ($action === 'create') {
            $newValues = array_diff_key($model->toArray(), array_flip(['created_at', 'updated_at', 'password']));
        } elseif ($action === 'delete') {
            $oldValues = array_diff_key($model->toArray(), array_flip(['created_at', 'updated_at', 'password']));
        }

        AuditLog::create([
            'user_id' => auth()->id(), // Operator (null for anonymous/system)
            'action' => $action,
            'entity' => get_class($model),
            'entity_id' => $model->getKey() ? (string) $model->getKey() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
        ]);
    }
}
