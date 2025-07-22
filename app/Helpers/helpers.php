<?php
// app/Helpers/helpers.php
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('setting_all')) {
    function setting_all()
    {
        return Setting::getAllGrouped();
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('userStorageUsage')) {
    function userStorageUsage(): array {
        $user = Auth::user();

        if (!$user || !$user->storage_limit) {
            return [
                'used' => 0,
                'free' => 0,
                'limit' => 0,
                'percentage' => 0,
                'formatted_used' => '0 B',
                'formatted_free' => '0 B',
                'formatted_limit' => '0 B',
            ];
        }

        $used = $user->reports()->withTrashed()->sum('size'); // assumed in bytes

        $limitMb = $user->storage_limit; // stored in MB
        $limit = $limitMb * 1024 * 1024; // convert MB to bytes

        $free = max(0, $limit - $used);
        $percentage = $limit > 0 ? round(($used / $limit) * 100, 2) : 0;

        return [
            'used' => $used,
            'free' => $free,
            'limit' => $limit,
            'percentage' => $percentage,
            'formatted_used' => formatBytes($used),
            'formatted_free' => formatBytes($free),
            'formatted_limit' => formatBytes($limit),
        ];
    }
}


if (!function_exists('file_color_class')) {
    function file_color_class($fileName, $isFolder = false)
    {
        if ($isFolder) {
            return 'text-warning';
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $colorMap = [
            'doc' => 'text-primary',
            'docx' => 'text-primary',
            'xls' => 'text-success',
            'xlsx' => 'text-success',
            'ppt' => 'text-danger',
            'pptx' => 'text-danger',
            'pdf' => 'text-danger',
            'png' => 'text-info',
            'jpg' => 'text-info',
            'jpeg' => 'text-info',
            'gif' => 'text-info',
            'exe' => 'text-secondary',
            'txt' => 'text-muted',
            'zip' => 'text-secondary',
            'rar' => 'text-secondary',
            '7z' => 'text-secondary',
            'tar' => 'text-secondary',
            'gz' => 'text-secondary',
        ];

        return $colorMap[$extension] ?? 'text-primary';
    }
}
