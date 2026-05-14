<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'user_id', 'test_package_id', 'started_at',
        'finished_at', 'status', 'time_spent_seconds'
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function testPackage() { return $this->belongsTo(TestPackage::class); }
    public function answers()     { return $this->hasMany(UserAnswer::class, 'session_id'); }
    public function result()      { return $this->hasOne(Result::class, 'session_id'); }
}