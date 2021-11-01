<?php

namespace App\Http\Resources\Api;

use App\Models\Resource;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request): array
    {
        /**
         * @var Resource $this
         */
        return [
            'id' => $this->id,
            'image_url' => $this->file_url,
        ];
    }
}
