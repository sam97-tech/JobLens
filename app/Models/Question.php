<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
 protected $fillable = [
    'job_post_id',
    'question',
    'difficulty'
 ];
 public function jobPost()
 {
    return
    $this->belongsTo(JobPost::class);
 }  
 public function answers()
 {
    return
    $this->hasMany(Answer::class);
 } //
}
