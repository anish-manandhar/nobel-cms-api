<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $faculty = $this->whenLoaded('faculty');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'faculty' => FacultyResource::make($faculty)
        ];
    }
}
