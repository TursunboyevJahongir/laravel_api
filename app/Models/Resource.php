<?php

namespace App\Models;

use App\Core\Models\CoreModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\URL;

class Resource extends CoreModel
{
    use HasFactory;

    protected $fillable = [
        'additional_identifier',
        'type',
        'path_512',
        'path_1024',
        'path_original',
        'resource'
    ];

    protected $hidden = ['additional_identifier', 'resource_type', 'resource_id', 'created_at', 'updated_at'];

    protected $appends = ['url_original', 'url_1024', 'url_512'];

    public function getUrlOriginalAttribute(): ?string
    {
        return URL::to($this->path_original);
    }

    public function getUrl1024Attribute(): ?string
    {
        return $this->attributes['path_1024'] ? URL::to($this->attributes['path_1024']) : null;
    }

    public function getUrl512Attribute(): ?string
    {
        return $this->attributes['path_512'] ? URL::to($this->attributes['path_512']) : null;
    }

    /**
     * @return MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
