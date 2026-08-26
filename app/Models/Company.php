<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
    'user_id',
    'company_name',
    'description',
    'website',
    'address',
    'logo'
  ];
   public function user()
  {
    return $this->belongsTo(User::class);
  } 
  public function jobPosts()
  {
    return $this->hasMany(JobPost::class);
  }  //
}
