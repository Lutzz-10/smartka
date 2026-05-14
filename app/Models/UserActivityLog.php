<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = ['user_id', 'action_type', 'detail'];

    protected $casts = ['detail' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}