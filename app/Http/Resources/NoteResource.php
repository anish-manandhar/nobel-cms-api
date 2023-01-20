<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
        'id'=> $this->id,
        'title'=> $this->title,
        'description' => $this->description,
        'path' => $this->path,
        'program' => ProgramResource::make($this->whenLoaded('programs')),
        'semester' => SemesterResource::make($this->whenLoaded('semesters')),
        'subject' => SubjectResource::make($this->whenLoaded('subjects')),
        ];
    }
}
