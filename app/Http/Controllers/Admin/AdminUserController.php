<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->latest();

        if ($request->filled('status')) {
            if ($request->status === 'premium') {
                $query->whereIn('subscription_status', ['premium', 'premium_plus']);
            } else {
                $query->where('subscription_status', 'free');
            }
        }
        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.pengguna.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['results', 'sessions', 'payments']);
        $recentSessions = $user->sessions()
            ->with(['testPackage', 'result'])
            ->latest()->limit(10)->get();
        $aiMessages = \App\Models\AiChatMessage::whereHas('session', fn($q) =>
            $q->where('user_id', $user->id)
        )->latest()->limit(20)->get();

        return view('admin.pengguna.show', compact('user', 'recentSessions', 'aiMessages'));
    }

    public function suspend(User $user)
    {
        // Toggle suspend dengan menonaktifkan email_verified_at
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = 'Akun pengguna berhasil disuspend.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = 'Akun pengguna berhasil diaktifkan kembali.';
        }
        return back()->with('success', $msg);
    }

    public function upgrade(Request $request, User $user)
    {
        $request->validate([
            'plan'   => 'required|in:premium,premium_plus',
            'months' => 'required|integer|min:1|max:12',
        ]);

        $user->activatePremium($request->plan, $request->months);

        return back()->with('success',
            'Akun ' . $user->name . ' berhasil di-upgrade ke ' . $request->plan . '!'
        );
    }

    public function resetPassword(User $user)
    {
        $newPass = 'Smartka' . rand(1000, 9999);
        $user->update(['password' => Hash::make($newPass)]);

        return back()->with('success',
            'Password direset. Password baru: ' . $newPass
        );
    }
}