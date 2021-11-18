<?php

namespace App\Http\Resources\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductResource extends JsonResource
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
            "title" => $this->title,
            "description" => $this->sub_description,
            "price" => $this->moneyFormatter($this->price),
            "slug" => $this->slug,
            "active" => $this->active,
            "main_image" => $this->mainImage?->file_url,
        ];
    }
}
