<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'plan', 'amount', 'payment_method',
        'gateway_transaction_id', 'status', 'callback_payload', 'paid_at'
    ];

    protected $casts = [
        'callback_payload' => 'array',
        'paid_at'          => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
}