<?php

namespace App\Http\Resources\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "name" => $this->name,
            "description" => $this->sub_description,
            "price" => $this->moneyFormatter($this->price),
            "main_image" => $this->mainImage?->file_url,
        ];
    }
}
