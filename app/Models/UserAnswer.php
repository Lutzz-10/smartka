<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'session_id', 'question_id', 'selected_answer',
        'is_correct', 'is_marked', 'hint_used', 'time_spent_seconds'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'is_marked'  => 'boolean',
        'hint_used'  => 'boolean',
    ];

    public function question() { return $this->belongsTo(Question::class); }
    public function session()  { return $this->belongsTo(UserSession::class, 'session_id'); }
}