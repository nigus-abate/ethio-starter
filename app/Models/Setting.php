<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'options',
        'label',
        'description',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
        'value' => 'string'
    ];

    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Handle boolean values
        if ($setting->type === 'boolean') {
            return (bool)$setting->value;
        }

        return $setting->value;
    }  

    public static function setValue($key, $value)
    {
        \Log::debug("Attempting to set value for key: {$key}", [
            'type' => is_object($value) ? get_class($value) : gettype($value),
            'value' => is_object($value) ? 'FILE_OBJECT' : $value
        ]);

        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            \Log::error("Setting not found for key: {$key}");
            return false;
        }

    // Handle file uploads
        if ($setting->type === 'file' && $value instanceof \Illuminate\Http\UploadedFile) {
            return self::processFileUpload($setting, $value);
        }

    // Handle regular values
        $setting->value = $setting->type === 'boolean' 
        ? ($value ? '1' : '0') 
        : $value;

        if (!$setting->save()) {
            \Log::error("Failed to save setting: {$key}");
            return false;
        }

        \Log::debug("Successfully saved setting: {$key}", [
            'new_value' => $setting->value
        ]);
        return true;
    }

    protected static function processFileUpload($setting, $file)
    {
        \Log::debug("Starting file upload for: {$setting->key}");
        
        try {
        // 1. Generate safe filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
            . '_' . time() . '.' . $extension;
            
        // 2. Ensure storage directory exists
            Storage::disk('public')->makeDirectory('settings');
            \Log::debug("Storage directory verified");
            
        // 3. Store the file
            $path = $file->storeAs('settings', $safeName, [
                'disk' => 'public',
                'visibility' => 'public'
            ]);
            \Log::debug("File stored at: {$path}");
            
        // 4. Verify physical storage
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("Physical file verification failed");
            }
            
        // 5. Delete old file if exists
            if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
                \Log::debug("Deleted old file: {$setting->value}");
            }
            
        // 6. Update database
            $setting->value = $path;
            if (!$setting->save()) {
                throw new \Exception("Database update failed");
            }
            
            \Log::debug("File upload completed successfully", [
                'db_path' => $path,
                'public_url' => Storage::disk('public')->url($path)
            ]);
            return true;
            
        } catch (\Exception $e) {
            \Log::error("File upload failed: " . $e->getMessage(), [
                'exception' => $e,
                'setting' => $setting->key,
                'file' => $originalName ?? 'unknown'
            ]);
            
        // Clean up if file was stored but DB failed
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            return false;
        }
    }


    public static function getAllGrouped()
    {
        return static::orderBy('group')
        ->orderBy('order')
        ->get()
        ->groupBy('group');
    }

    public function getOptionsAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true);
    }
}