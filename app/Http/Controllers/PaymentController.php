<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private array $plans = [
        'premium' => [
            'name'       => 'SMARTKA Premium',
            'price'      => 79000,
            'price_year' => 699000,
        ],
        'premium_plus' => [
            'name'       => 'SMARTKA Premium Plus',
            'price'      => 129000,
            'price_year' => 1199000,
        ],
    ];

    // ── Halaman Checkout ──────────────────────────────
    public function checkout(string $plan)
    {
        if (!array_key_exists($plan, $this->plans)) {
            return redirect()->route('premium')->with('error', 'Paket tidak valid.');
        }

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $planData = $this->plans[$plan];

        $paymentMethods = [
            'bank_transfer' => [
                'label' => 'Transfer Bank',
                'icon'  => '🏦',
                'banks' => ['BCA', 'BNI', 'BRI', 'Mandiri'],
            ],
            'ewallet' => [
                'label'   => 'E-Wallet',
                'icon'    => '📱',
                'wallets' => ['GoPay', 'OVO', 'DANA', 'ShopeePay'],
            ],
            'qris' => [
                'label' => 'QRIS',
                'icon'  => '📷',
            ],
            'credit_card' => [
                'label' => 'Kartu Kredit / Debit',
                'icon'  => '💳',
            ],
        ];

        return view('premium.checkout', compact('plan', 'planData', 'user', 'paymentMethods'));
    }

    // ── Proses Pembayaran ─────────────────────────────
    public function process(Request $request)
    {
        $request->validate([
            'plan'           => 'required|in:premium,premium_plus',
            'period'         => 'required|in:monthly,yearly',
            'payment_method' => 'required|string',
            'promo_code'     => 'nullable|string|max:20',
        ]);

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $plan     = $request->plan;
        $period   = $request->period;
        $planData = $this->plans[$plan];

        // Hitung harga
        $amount = $period === 'yearly'
            ? $planData['price_year']
            : $planData['price'];

        // Diskon promo (simulasi)
        $discount = 0;
        if ($request->promo_code === 'SMARTKA10') {
            $discount = (int) ($amount * 0.10);
            $amount   = $amount - $discount;
        }

        // Buat record payment
        $payment = Payment::create([
            'user_id'        => $user->id,
            'plan'           => $plan,
            'amount'         => $amount,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
        ]);

        // Untuk simulasi development — langsung sukses
        // Di production, integrasikan dengan Midtrans/Xendit
        if (app()->environment('local')) {
            return redirect()->route('payment.status', $payment->id)
                ->with('simulate', true);
        }

        return redirect()->route('payment.status', $payment->id);
    }

    // ── Halaman Status Pembayaran ─────────────────────
    public function status(Payment $payment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan payment milik user ini
        if ($payment->user_id !== $user->id) {
            abort(403);
        }

        // Simulasi: jika dari local, langsung aktivasi
        if (session('simulate') && $payment->status === 'pending') {
            $this->activateSubscription($payment);
            $payment->refresh();
        }

        return view('premium.status', compact('payment', 'user'));
    }

    // ── Webhook Callback Payment Gateway ─────────────
    public function callback(Request $request)
    {
        Log::info('Payment callback', $request->all());

        // Implementasi verifikasi signature sesuai gateway
        // Contoh untuk Midtrans:
        $orderId       = $request->order_id ?? null;
        $transactionId = $request->transaction_id ?? null;
        $statusCode    = $request->status_code ?? null;
        $grossAmount   = $request->gross_amount ?? null;

        if (!$orderId) {
            return response()->json(['error' => 'Invalid callback'], 400);
        }

        $payment = Payment::find($orderId);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        if ($statusCode === '200') {
            $this->activateSubscription($payment);
        } elseif (in_array($statusCode, ['201', '202'])) {
            $payment->update(['status' => 'pending']);
        } else {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['ok' => true]);
    }

    // ── Helper: Aktivasi langganan ────────────────────
    private function activateSubscription(Payment $payment): void
    {
        $months = str_contains($payment->payment_method ?? '', 'yearly') ? 12 : 1;

        // Update payment
        $payment->update([
            'status'  => 'success',
            'paid_at' => now(),
        ]);

        // Update user
        $payment->user->update([
            'subscription_status'  => $payment->plan,
            'subscription_ends_at' => now()->addMonths($months),
        ]);

        // Buat record subscription
        Subscription::create([
            'user_id'        => $payment->user_id,
            'plan'           => $payment->plan,
            'start_date'     => now(),
            'end_date'       => now()->addMonths($months),
            'payment_status' => 'success',
            'amount'         => $payment->amount,
            'payment_method' => $payment->payment_method,
            'transaction_id' => $payment->gateway_transaction_id,
        ]);
    }
}