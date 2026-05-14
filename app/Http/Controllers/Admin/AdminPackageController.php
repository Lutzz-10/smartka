<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPackageController extends Controller
{
    public function index()
    {
        $packages = TestPackage::with('createdBy')
            ->withCount('questions')
            ->latest()
            ->paginate(15);

        return view('admin.paket.index', compact('packages'));
    }

    public function create()
    {
        $subjects  = Subject::all();
        $questions = Question::with(['subject', 'topic'])
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('admin.paket.create', compact('subjects', 'questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:200',
            'class_level'      => 'required|in:6,9,12',
            'duration_minutes' => 'required|integer|min:10',
            'type'             => 'required|in:free,premium',
            'status'           => 'required|in:draft,published',
            'question_ids'     => 'required|array|min:1',
            'question_ids.*'   => 'exists:questions,id',
        ]);

        $package = TestPackage::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'class_level'      => $request->class_level,
            'total_questions'  => count($request->question_ids),
            'duration_minutes' => $request->duration_minutes,
            'type'             => $request->type,
            'is_randomized'    => $request->boolean('is_randomized'),
            'available_from'   => $request->available_from,
            'available_until'  => $request->available_until,
            'status'           => $request->status,
            'created_by'       => Auth::id(),
        ]);

        // Attach questions
        foreach ($request->question_ids as $order => $questionId) {
            $package->questions()->attach($questionId, ['order_number' => $order + 1]);
        }

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket latihan berhasil dibuat!');
    }

    public function edit(TestPackage $package)
    {
        $package->load('questions');
        $subjects  = Subject::all();
        $questions = Question::with(['subject', 'topic'])
            ->where('status', 'active')->get();

        return view('admin.paket.edit', compact('package', 'subjects', 'questions'));
    }

    public function update(Request $request, TestPackage $package)
    {
        $request->validate([
            'name'             => 'required|string|max:200',
            'duration_minutes' => 'required|integer|min:10',
            'status'           => 'required|in:draft,published',
        ]);

        $package->update($request->except(['_token', '_method', 'question_ids']));

        if ($request->filled('question_ids')) {
            $package->questions()->detach();
            foreach ($request->question_ids as $order => $questionId) {
                $package->questions()->attach($questionId, ['order_number' => $order + 1]);
            }
            $package->update(['total_questions' => count($request->question_ids)]);
        }

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket latihan berhasil diperbarui!');
    }

    public function destroy(TestPackage $package)
    {
        $package->questions()->detach();
        $package->delete();
        return back()->with('success', 'Paket berhasil dihapus!');
    }
}