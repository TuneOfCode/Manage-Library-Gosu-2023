<?php

namespace App\Http\Resources\V1\Auth;

use App\Http\Resources\V1\Book\BookResourceCollection;
use App\Http\Resources\V1\Package\PackageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource {
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
            'roles' => $this->roles()->pluck('name')->toArray(),
            'permissions' => $this->getPermissionsViaRoles()->pluck('name')->toArray(),
            'package' => new PackageResource($this->package),
            'books' => new BookResourceCollection($this->books),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
