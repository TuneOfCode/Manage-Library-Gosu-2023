<?php

namespace App\Http\Resources\V1\Package;

use App\Http\Resources\V1\User\UserResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'price' => (float) $this->price,
            'discount' => (float) $this->discount,
            'description' => $this->description,
            'isActive' => $this->is_active,
            'users' => new UserResourceCollection($this->whenLoaded('users')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
