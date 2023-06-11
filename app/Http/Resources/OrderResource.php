<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return dd($this->balanceBefore($request->user_id));
        return [
            'datetime' => $this->created_at->format('Y-m-d H:i:s'),
            'id' => $this->id,
            'type' => $this->order_type,
            'product_id' => $this->product_id,
            'receiver_id' => $this->receiver_id,
            'amount' => $this->amount,
            'balance_before' => $this->balanceBefore($request->user_id),
            'balance_after' => $this->balanceAfter($request->user_id),
            'status' => $this->status == 1 ? 'active' : 'pending',
            'product' => $this->whenLoaded('product') ? new ProductResource($this->product) : null,
        ];
    }
}
