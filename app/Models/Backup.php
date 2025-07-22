<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Backup extends Model
{
    use HasFactory, LogsActivity;

    // Add these to prevent logging of timestamps
    protected static $ignoreChangedAttributes = ['updated_at'];

    protected $fillable = [
        'name',
        'disk',
        'path',
        'size',
        'type',
        'status',
        'metadata',
        'user_id',
        'completed_at'
    ];

    protected $casts = [
        'path' => 'string',
        'metadata' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getDownloadUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function isRestorable()
    {
        return $this->status === 'completed' && Storage::disk($this->disk)->exists($this->path);
    }

    public function getRestoreOptionsAttribute()
    {
        $options = [
            'database' => false,
            'storage' => false
        ];

        if (isset($this->metadata['contents']['database'])) {
            $options['database'] = $this->metadata['contents']['database'];
        }

        if (isset($this->metadata['contents']['storage'])) {
            $options['storage'] = $this->metadata['contents']['storage'];
        }

        return $options;
    }
}