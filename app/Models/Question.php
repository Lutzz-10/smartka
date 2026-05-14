<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subject_id', 'topic_id', 'class_level', 'difficulty', 'type',
        'question_text', 'question_image', 'option_a', 'option_b',
        'option_c', 'option_d', 'option_e', 'correct_answer',
        'explanation_text', 'explanation_video_url', 'source', 'status', 'created_by'
    ];

    public function subject()   { return $this->belongsTo(Subject::class); }
    public function topic()     { return $this->belongsTo(Topic::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}