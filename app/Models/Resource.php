<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Class Resource
 * @package App\Models
 * @property string $id
 * @property string $additional_identifier
 * @property string $name
 * @property string $type
 * @property string $full_url
 * @property string $resource_type
 * @property string $resource_id
 * @property string $file_url URL файла
 */
class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'additional_identifier',
        'name',
        'type',
        'full_url',
        'resource'
    ];

    public static function uploadFile(UploadedFile $file, $model)
    {
        return Storage::disk('public')->putFile('/uploads/' .$model, $file);
    }

    public function removeFile()
    {
        @unlink(public_path($this->full_url));
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->full_url ? URL::to($this->full_url) : null;
    }
    /**
     * @return MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
