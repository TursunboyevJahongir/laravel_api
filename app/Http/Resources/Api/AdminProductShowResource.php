<?php

namespace App\Http\Resources\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var Product $this
         */
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "is_active" => $this->is_active,
            "title" => $this->title,
            "description" => $this->description ?? null,
            "price" => $this->moneyFormatter($this->price),
            "main_image" => $this->mainImage?->file_url,
            "video" => $this->video?->file_url,
            "images" => ImageResource::collection($this->images),
        ];
    }
}
