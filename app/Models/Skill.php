<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
    public function jobPosts()
    {
        return
        $this->belongsToMany(JobPost::class, 'job_skill');
    }
    public function users()
    {
        return
        $this->belongsToMany(User::class,'applicant_skill');
    }
    //
}
