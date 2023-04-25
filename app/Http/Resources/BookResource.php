<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            // "categoryId" => $this->category_id,
            "category" => new CategoryResource($this->category),
            "name" => $this->name,
            "image" => $this->image,
            "quantity" => $this->quantity,  
            "price" => $this->price,
            "loanPrice" => $this->loan_price,
            "status" => $this->status,
            "author" => $this->author,
            "publishedAt" => $this->published_at,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at
        ];
    }
}
