<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    protected $fillable = [
        'name', 'description', 'class_level', 'total_questions',
        'duration_minutes', 'type', 'is_randomized',
        'available_from', 'available_until', 'status', 'created_by'
    ];

    protected $casts = [
        'available_from'  => 'datetime',
        'available_until' => 'datetime',
        'is_randomized'   => 'boolean',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'test_package_questions')
                    ->withPivot('order_number')
                    ->orderBy('order_number');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}