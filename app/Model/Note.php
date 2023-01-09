<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];

    /**
     * Get recursion items by parent_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, "parent_id", "id");
    }
}
