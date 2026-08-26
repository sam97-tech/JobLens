<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
 protected $fillable = [
    'application_id',
    'question_id',
    'answer',
    'voice_analysis',
    'face_analysis',
    'score'
 ];  
 public function application()
 
 {
    return
    $this->belongsTo(Application::class);
 }
 public function question()
 {
    return
    $this->belongsTo(Question::class);
 }//
}
