<?php

namespace App\Http\Resources\Cryptocurrency;

use Illuminate\Http\Resources\Json\JsonResource;

class CoinPriceResource extends JsonResource
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
            'coin' => $this['coin'],
            'price' => $this['price'],
        ];
    }
}
