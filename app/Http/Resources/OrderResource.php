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
        return [
            'id' => $this->id,
            'type' => $this->order_type,
            'product_id' => $this->product_id,
            'receiver_id' => $this->receiver_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'product' => $this->whenLoaded('product') ? new ProductResource($this->product) : null,
        ];
    }
}
