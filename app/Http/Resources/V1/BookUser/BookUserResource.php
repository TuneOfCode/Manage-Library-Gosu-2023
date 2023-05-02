<?php

namespace App\Http\Resources\V1\BookUser;

use App\Http\Resources\V1\Book\BookResource;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookUserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'book' => new BookResource($this->whenLoaded('book')),
            'amount' => $this->amount,
            'payment' => $this->payment,
            'discount' => $this->discount,
            'unit' => $this->unit,
            'extraMoney' => $this->extra_money,
            'status' => $this->status,
            'approvedAt' => $this->approved_at,
            'rejectedAt' => $this->rejected_at,
            'canceledAt' => $this->canceled_at,
            'paidAt' => $this->paid_at,
            'borrowedAt' => $this->borrowed_at,
            'estimatedReturnedAt' => $this->estimated_returned_at,
            'returnedAt' => $this->returned_at,
            'extraMoneyAt' => $this->extra_money_at,
            'note' => $this->note,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
