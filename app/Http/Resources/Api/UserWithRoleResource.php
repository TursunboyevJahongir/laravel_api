<?php

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithRoleResource extends JsonResource
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
         * @var User $this
         */
        return [
            "id" => $this->id,
            "name" => $this->full_name,
            "phone" => $this->phone,
            'roles' => RoleResource::collection($this->roles()->get()),
        ];
    }
}
