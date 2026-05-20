<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembahasanController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $classLevel = $user->class_level;

        // Fetch active subjects with their related topics for the student's class level
        $subjects = Subject::where('class_level', $classLevel)
            ->where('is_active', true)
            ->with(['topics' => function ($query) {
                $query->orderBy('order_number', 'asc');
            }])
            ->withCount('questions')
            ->orderBy('name', 'asc')
            ->get();

        return view('pembahasan.index', compact('subjects', 'classLevel'));
    }
}
