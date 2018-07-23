<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $fillable = [
		'name', 'starts_at', 'ends_at', 'is_finished', 'additional_cost'
    ];

    /**
     * Make relation between the project and the tasks
     *
     * @return mixed
     */
    public function project() {
    	return $this->belongsTo(User::class);
    }
}
