<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'total_score',
        'correct_count', 'wrong_count', 'empty_count',
        'score_per_subject', 'weakness_topics'
    ];

    protected $casts = [
        'score_per_subject' => 'array',
        'weakness_topics'   => 'array',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function session() { return $this->belongsTo(UserSession::class, 'session_id'); }
}