<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $fillable = [
		'name', 'description', 'links', 'billing'
    ];

    public function user() {
    	return $this->belongsTo(User::class);
    }
}
