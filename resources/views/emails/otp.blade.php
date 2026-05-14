<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: 'Inter', Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
    .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .header { background: #1a56db; padding: 32px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 28px; letter-spacing: 2px; }
    .header p  { color: #bfdbfe; margin: 4px 0 0; font-size: 13px; }
    .body { padding: 36px 32px; }
    .body p { color: #374151; font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
    .otp-box { background: #eff6ff; border: 2px dashed #1a56db; border-radius: 12px; text-align: center; padding: 24px; margin: 24px 0; }
    .otp-box span { font-size: 42px; font-weight: 800; color: #1a56db; letter-spacing: 10px; }
    .note { background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px; padding: 12px 16px; font-size: 13px; color: #92400e; }
    .footer { background: #f9fafb; padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>SMARTKA</h1>
      <p>Belajar Cerdas, Raih Prestasi Terbaik</p>
    </div>
    <div class="body">
      <p>Halo, <strong>{{ $user->name }}</strong>!</p>
      <p>Gunakan kode berikut untuk verifikasi akun SMARTKA kamu:</p>
      <div class="otp-box">
        <span>{{ $otp }}</span>
      </div>
      <div class="note">
        ⏱ Kode ini hanya berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini ke siapapun.
      </div>
      <p style="margin-top:20px;">Jika kamu tidak merasa mendaftar di SMARTKA, abaikan email ini.</p>
    </div>
    <div class="footer">
      © {{ date('Y') }} SMARTKA — Platform Belajar Siswa Indonesia
    </div>
  </div>
</body>
</html>