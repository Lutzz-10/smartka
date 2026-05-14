<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    // ─── Fillable ─────────────────────────────────────────
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'class_level',
        'avatar',
        'otp_code',
        'otp_expires_at',
        'subscription_status',
        'subscription_ends_at',
    ];

    // ─── Hidden ───────────────────────────────────────────
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    // ─── Casts ────────────────────────────────────────────
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'otp_expires_at'       => 'datetime',
        'subscription_ends_at' => 'datetime',
        'password'             => 'hashed',
    ];

    // ═══════════════════════════════════════════════════════
    // RELASI
    // ═══════════════════════════════════════════════════════

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function aiSessions()
    {
        return $this->hasMany(AiChatSession::class);
    }

    public function aiDailyUsage()
    {
        return $this->hasMany(AiDailyUsage::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    // ═══════════════════════════════════════════════════════
    // HELPER METHODS
    // ═══════════════════════════════════════════════════════

    /**
     * Cek apakah user adalah premium aktif
     */
    public function isPremium(): bool
    {
        return in_array($this->subscription_status, ['premium', 'premium_plus'])
            && (
                $this->subscription_ends_at === null
                || $this->subscription_ends_at->isFuture()
            );
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Ambil sisa kuota AI hari ini
     */
    public function todayAiQuota(): int
    {
        // Premium tidak ada limit
        if ($this->isPremium()) {
            return 999;
        }

        $limit = (int) Setting::get('ai_daily_free_limit', 5);

        $used = AiDailyUsage::where('user_id', $this->id)
            ->where('date', today()->toDateString())
            ->value('count') ?? 0;

        return max(0, $limit - $used);
    }

    /**
     * Ambil nama jenjang lengkap
     */
    public function getClassLevelLabelAttribute(): string
    {
        return match($this->class_level) {
            '6'     => 'Kelas 6 SD',
            '9'     => 'Kelas 9 SMP',
            '12'    => 'Kelas 12 SMA',
            default => 'Tidak diketahui',
        };
    }

    /**
     * Ambil label status langganan
     */
    public function getSubscriptionLabelAttribute(): string
    {
        return match($this->subscription_status) {
            'premium'      => 'Premium',
            'premium_plus' => 'Premium Plus',
            default        => 'Gratis',
        };
    }

    /**
     * Cek apakah email sudah diverifikasi
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Ambil rata-rata skor semua latihan
     */
    public function getAverageScore(): float
    {
        return round($this->results()->avg('total_score') ?? 0, 1);
    }

    /**
     * Ambil topik lemah dari hasil terbaru
     */
    public function getWeakTopics(): array
    {
        return $this->results()
            ->latest()
            ->first()
            ?->weakness_topics ?? [];
    }

    /**
     * Ambil total soal yang sudah dikerjakan
     */
    public function getTotalAnswered(): int
    {
        return $this->sessions()
            ->where('status', 'completed')
            ->withCount('answers')
            ->get()
            ->sum('answers_count');
    }

    /**
     * Cek apakah OTP masih valid
     */
    public function isOtpValid(string $otp): bool
    {
        return $this->otp_code === $otp
            && $this->otp_expires_at !== null
            && Carbon::now()->isBefore($this->otp_expires_at);
    }

    /**
     * Aktifkan langganan premium
     */
    public function activatePremium(string $plan = 'premium', int $months = 1): void
    {
        $this->update([
            'subscription_status'   => $plan,
            'subscription_ends_at'  => now()->addMonths($months),
        ]);
    }

    /**
     * Reset ke free
     */
    public function resetToFree(): void
    {
        $this->update([
            'subscription_status'   => 'free',
            'subscription_ends_at'  => null,
        ]);
    }
}