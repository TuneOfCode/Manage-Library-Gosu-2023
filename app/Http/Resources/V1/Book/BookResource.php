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
            'privot' => $this->whenPivotLoaded('book_user', function () {
                return [
                    'amount' => $this->pivot->amount,
                    'payment' => $this->pivot->payment,
                    'discount' => $this->pivot->discount,
                    'unit' => $this->pivot->unit,
                    'extraMoney' => $this->pivot->extra_money,
                    'status' => $this->pivot->status,
                    'approvedAt' => $this->pivot->approved_at,
                    'rejectedAt' => $this->pivot->rejected_at,
                    'canceledAt' => $this->pivot->canceled_at,
                    'paidAt' => $this->pivot->paid_at,
                    'borrowedAt' => $this->pivot->borrowed_at,
                    'estimatedReturnedAt' => $this->pivot->estimated_returned_at,
                    'returnedAt' => $this->pivot->returned_at,
                    'extraMoneyAt' => $this->pivot->extra_money_at,
                    'note' => $this->pivot->note,
                ];
            }),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
