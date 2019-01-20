<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //public $timestamps = false;

    protected $fillable = [
        'title', 'credits'
    ];

    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }
}
