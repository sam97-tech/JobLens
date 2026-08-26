<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
  protected $fillable = [
    'user_id','job_post_id','cv_path','status'
  ]; 
  public function user(){
    return $this->belongsTo(User::class);
  }
  public function jobPost()
  {
    return $this->belongsTo(JobPost::class);
  }
  public function answers()
  {
    return
    $this->hasMany(Answer::class);
  }
  public function interview()
  {
    return
    $this->hasOne(Interview::class);
  }
   //
}
