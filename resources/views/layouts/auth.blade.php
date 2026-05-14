<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SMARTKA') — Belajar Cerdas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1,h2,h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
    .otp-input:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,0.15); }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  @yield('content')
</body>
</html>