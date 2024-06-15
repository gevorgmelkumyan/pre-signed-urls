<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $status
 *
 * @property string $url
 * @property string $date
 */
class File extends Model {
    use HasFactory;

    const STATUS_UPLOADING = 'uploading';
    const STATUS_UPLOADED = 'uploaded';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'name',
        'path',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'url',
        'date',
    ];

    public function getUrlAttribute(): ?string {
        return $this->path ? Storage::temporaryUrl($this->path, now()->addMinutes(30)) : null;
    }

    public function getDateAttribute(): string {
        return $this->updated_at->diffForHumans();
    }
}
