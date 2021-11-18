<?php

namespace App\Http\Resources\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
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
         * @var Category $this
         */
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "position" => $this->position,
            "active" => $this->active,
            "ico" => $this->ico?->file_url,
        ];
    }
}
