<?php

namespace App\Http\Resources\V1\Book;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\User\UserResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'position' => $this->position,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'loanPrice' => $this->loan_price,
            'status' => $this->status,
            'author' => $this->author,
            'publishedAt' => $this->published_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'members' => new UserResourceCollection($this->whenLoaded('users')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
