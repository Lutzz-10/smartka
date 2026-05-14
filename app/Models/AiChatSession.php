<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $fillable = ['user_id', 'title', 'subject', 'message_count'];

    public function user()     { return $this->belongsTo(User::class); }
    public function messages() { return $this->hasMany(AiChatMessage::class, 'session_id'); }
}