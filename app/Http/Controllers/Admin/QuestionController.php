<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'topic'])
            ->latest();

        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->paginate(20)->withQueryString();
        $subjects  = Subject::all();

        return view('admin.soal.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::with('topics')->get();
        return view('admin.soal.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'topic_id'     => 'required|exists:topics,id',
            'class_level'  => 'required|in:6,9,12',
            'difficulty'   => 'required|in:easy,medium,hard',
            'type'         => 'required|in:multiple_choice,true_false,short_answer',
            'question_text'=> 'required|string',
            'option_a'     => 'required_if:type,multiple_choice',
            'option_b'     => 'required_if:type,multiple_choice',
            'option_c'     => 'required_if:type,multiple_choice',
            'option_d'     => 'required_if:type,multiple_choice',
            'correct_answer'    => 'required|string',
            'explanation_text'  => 'nullable|string',
            'status'            => 'required|in:draft,active,archived',
        ]);

        $data               = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')
                ->store('questions', 'public');
        }

        Question::create($data);

        return redirect()->route('admin.soal.index')
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    public function edit(Question $question)
    {
        $subjects = Subject::with('topics')->get();
        return view('admin.soal.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'topic_id'      => 'required|exists:topics,id',
            'class_level'   => 'required|in:6,9,12',
            'difficulty'    => 'required|in:easy,medium,hard',
            'question_text' => 'required|string',
            'correct_answer'=> 'required|string',
            'status'        => 'required|in:draft,active,archived',
        ]);

        $data = $request->except(['_token', '_method', 'question_image']);

        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')
                ->store('questions', 'public');
        }

        $question->update($data);

        return redirect()->route('admin.soal.index')
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus!');
    }
}