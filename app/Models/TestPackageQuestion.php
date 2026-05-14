<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestPackageQuestion extends Model
{
    protected $fillable = ['test_package_id', 'question_id', 'order_number'];
}