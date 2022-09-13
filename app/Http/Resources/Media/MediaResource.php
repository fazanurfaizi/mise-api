<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\Conversions\Conversion;

class MediaResource extends JsonResource
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
            'order_column' => $this->order_column,
            'file_name' => $this->file_name,
            'mime_Type' => $this->mime_type,
            'size' => $this->size,
            'original_url' => $this->original_url,
            'preview_url' => $this->preview_url,
            'responsive_urls' => $this->getResponsiveImageUrls()
        ];
    }
}
