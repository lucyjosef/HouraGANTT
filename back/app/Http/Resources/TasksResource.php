<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TasksResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'is_finished' => $this->is_finished,
            'additional_cost' => $this->additional_cost,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'project_id' => $this->project_id,
            'speciality_id' => $this->speciality_id,
            'resource_id' => $this->resource_id,
            'resource' => new ResourcesResource($this->resource),
        ];
    }
}
