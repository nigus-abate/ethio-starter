<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Services\ActivityLogger;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            $model->logActivity('created', Auth::user(), [], class_basename($model) . ' created');
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // optional: ignore timestamps

            if (!empty($changes)) {
                $model->logActivity('updated', Auth::user(), ['changed' => $changes], class_basename($model) . ' updated');
            }
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted', Auth::user(), [], class_basename($model) . ' deleted');
        });
    }

    public function logActivity(
        string $action,
        ?Model $causer = null,
        array $properties = [],
        ?string $description = null
    ) {
        $causer = $causer ?? auth()->user(); // fallback to logged-in user if no causer passed

        return ActivityLogger::log(
            action: $this->formatActionName($action),
            causer: $causer,
            subject: $this,
            properties: $properties,
            description: $description,
            ipAddress: Request::ip() ?? 'system',
            userAgent: Request::header('User-Agent') ?? 'system'
        );
    }

    protected function formatActionName(string $action): string
    {
        return strtolower(class_basename($this)) . '.' . $action;
    }

    // protected function getDefaultDescription(string $action): string
    // {
    //     if ($this instanceof Backup) {
    //         return match($action) {
    //             'created' => "New backup '{$this->name}' created",
    //             'updated' => "Backup '{$this->name}' updated",
    //             'deleted' => "Backup '{$this->name}' deleted",
    //             default => class_basename($this) . " {$action}"
    //         };
    //     }
        
    //     return class_basename($this) . " {$action}";
    // }
}
