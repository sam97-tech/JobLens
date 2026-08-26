<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
  protected $fillable = [
    'company_id',
    'title',
    'description',
    'requirements',
    'location',
    'salary',
    'status'
  ]; 
  public function company()
  {
    return $this->belongsTo(Company::class);
  }
   public function skills()
  {
    return $this->belongsToMany(Skill::class, 'job_skill');
  }
  public function applications()
  {
    return $this->hasMany(Application::class);
  } //
}
