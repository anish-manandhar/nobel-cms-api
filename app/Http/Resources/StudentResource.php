<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'roll_number' => $this->roll_number,
            'registration_number' => $this->registration_number,
            'guardian_name' => $this->guardian_name,
            'guardian_number' => $this->guardian_number,
            'joined_at' => $this->joined_at,
            'program' => ProgramResource::make($this->whenLoaded('programs')),
            'semester' => SemesterResource::make($this->whenLoaded('semesters')),
        ];
    }
}
