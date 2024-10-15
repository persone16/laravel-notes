<?php

namespace App\Http\Resources\Notes;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'id'          => $this->id,
            'parent_id'   => $this->parent_id,
            'owner_id'    => $this->owner_id,
            'type'        => $this->type,
            'title'       => $this->title,
            'description' => $this->description,
            'order'       => $this->order,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
