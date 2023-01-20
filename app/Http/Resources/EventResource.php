<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'title'=> $this->title,
            'description'=> $this->description,
            'type'=> $this->type,
            'created_by'=> UserResource::make($this->whenLoaded('created_by')),
            'updated_by'=> UserResource::make($this->whenLoaded('updated_by')),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'images' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
