<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'email' => $this->email,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'address' => $this->address,
            'score' => $this->score,
            'status' => $this->status,
            'balance' => $this->balance,
            'package' => $this->whenLoaded('package'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
