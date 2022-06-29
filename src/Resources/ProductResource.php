<?php

namespace Rcdelfin\Inventory\Resources;

use Rcdelfin\Inventory\Resources\AttributeResource;
use Rcdelfin\Inventory\Resources\VariantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->hasSku() ? $this->skus()->first()->code : null,
			'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'cost' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'is_active' => $this->is_active,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'attributes' => AttributeResource::collection($this->attributes)->toArray(app('request')),
            'variations' => $this->when($this->hasAttributes() && $this->hasSku(), 
                VariantResource::collection($this->skus)->toArray(app('request'))
            )
        ];
    }
}