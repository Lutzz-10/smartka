<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PremiumController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $plans = [
            'free' => [
                'name'     => 'Free',
                'price'    => 0,
                'period'   => 'Selamanya gratis',
                'color'    => 'gray',
                'popular'  => false,
                'features' => [
                    ['ok' => true,  'text' => '20 soal per hari'],
                    ['ok' => true,  'text' => '1 try out per bulan'],
                    ['ok' => true,  'text' => '5 pertanyaan AI / hari'],
                    ['ok' => true,  'text' => 'Analisis dasar'],
                    ['ok' => false, 'text' => 'Soal tidak terbatas'],
                    ['ok' => false, 'text' => 'Pembahasan video'],
                    ['ok' => false, 'text' => 'AI Chat tanpa batas'],
                    ['ok' => false, 'text' => 'Analisis AI lengkap'],
                    ['ok' => false, 'text' => 'Laporan orang tua'],
                    ['ok' => false, 'text' => 'Konsultasi guru'],
                ],
            ],
            'premium' => [
                'name'        => 'Premium',
                'price'       => 79000,
                'price_year'  => 699000,
                'period'      => 'per bulan',
                'period_year' => 'per tahun (hemat 26%)',
                'color'       => 'blue',
                'popular'     => true,
                'features'    => [
                    ['ok' => true, 'text' => 'Soal tidak terbatas'],
                    ['ok' => true, 'text' => 'Try out tak terbatas'],
                    ['ok' => true, 'text' => 'AI Chat tanpa batas'],
                    ['ok' => true, 'text' => 'Pembahasan video lengkap'],
                    ['ok' => true, 'text' => 'Analisis AI mendalam'],
                    ['ok' => true, 'text' => 'Hint & bantuan soal'],
                    ['ok' => true, 'text' => 'Prioritas dukungan'],
                    ['ok' => false, 'text' => 'Laporan orang tua'],
                    ['ok' => false, 'text' => 'Konsultasi guru'],
                ],
            ],
            'premium_plus' => [
                'name'        => 'Premium Plus',
                'price'       => 129000,
                'price_year'  => 1199000,
                'period'      => 'per bulan',
                'period_year' => 'per tahun (hemat 23%)',
                'color'       => 'yellow',
                'popular'     => false,
                'features'    => [
                    ['ok' => true, 'text' => 'Semua fitur Premium'],
                    ['ok' => true, 'text' => 'Laporan ke orang tua'],
                    ['ok' => true, 'text' => 'Konsultasi guru 2x/bulan'],
                    ['ok' => true, 'text' => 'Prioritas dukungan VIP'],
                    ['ok' => true, 'text' => 'Akses beta fitur baru'],
                ],
            ],
        ];

        return view('premium.index', compact('user', 'plans'));
    }
}