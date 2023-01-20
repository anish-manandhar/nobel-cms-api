<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=> $this->name,
            'email'=> $this->email,
            'phone'=> $this->phone,
            'address'=> $this->address,
            'gender' => $this->gender,
            'role' => $this->role,
            'date_of_birth'=> $this->date_of_birth,
            'student_details' => StudentResource::make($this->whenLoaded('student_details')),
            'employee_details' => EmployeeResource::make($this->whenLoaded('employees_details')),
            'images' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
