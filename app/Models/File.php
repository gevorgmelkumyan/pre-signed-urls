<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $status
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
}
