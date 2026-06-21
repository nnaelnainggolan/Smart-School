<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak — Smart School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#1E3A5F', secondary: '#2E86AB', accent: '#F4A261' } } } }</script>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary to-secondary flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-md text-center">
        <div class="w-20 h-20 bg-red-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-lock text-red-500 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-black text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 mb-6">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:bg-secondary transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</body>
</html>
