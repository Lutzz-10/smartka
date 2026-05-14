<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDailyUsage extends Model
{
    protected $fillable = ['user_id', 'date', 'count'];

    protected $casts = ['date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
}