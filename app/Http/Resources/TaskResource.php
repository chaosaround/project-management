<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform Task into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
	    return [
		    'id' => $this->id,
		    'name' => $this->name,
		    'priority' => $this->priority,
		    'project' => $this->project,
		    'created_at' => (string) $this->created_at,
		    'updated_at' => (string) $this->updated_at
	    ];
    }
}
