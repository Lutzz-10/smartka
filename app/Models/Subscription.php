<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan', 'start_date', 'end_date',
        'payment_status', 'amount', 'payment_method', 'transaction_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
}