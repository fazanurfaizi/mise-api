<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductAttributeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $limit = request('limit');
        $hasLimit = $limit ? '&limit=' . $limit : '';

        return [
            'data' => AttributeResource::collection($this->collection),
            'pagination' => [
                "current_page" => $this->currentPage(),
                "first_page_url" => $this->getOptions()['path'].'?'.$this->getOptions()['pageName'].'=1'.$hasLimit,
                "prev_page_url" => $this->previousPageUrl().$hasLimit,
                "next_page_url" => $this->nextPageUrl().$hasLimit,
                "last_page_url" => $this->getOptions()['path'].'?'.$this->getOptions()['pageName'].'='.$this->lastPage().$hasLimit,
                "last_page" => $this->lastPage(),
                "per_page" => $this->perPage(),
                "total" => $this->total(),
                "path" => $this->getOptions()['path'],
            ],
        ];
    }
}
