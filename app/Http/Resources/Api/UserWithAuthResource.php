<?php

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithAuthResource extends JsonResource
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
            "_token" => $this->auth_token,
            "id" => $this->id,
            "full_name" => $this->full_name,
            "phone" => $this->phone,
            'roles' => RoleResource::collection($this->roles()->get()),
            'permissions' => $this->getAllPermissions()->pluck('name')->toArray()
        ];
    }
}
