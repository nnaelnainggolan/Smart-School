<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Smart School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#1E3A5F', secondary: '#2E86AB', accent: '#F4A261' } } }
        }
    </script>
    <style>
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex relative">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/sekolah.jpg') }}');"></div>
    <div class="absolute inset-0 bg-primary/80"></div>
    <!-- Konten di atas background, beri z-10 -->

    <!-- Left Panel (Decorative) -->
    <div class="hidden lg:flex flex-col justify-center items-center flex-1 text-white p-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-80 h-80 bg-white/5 rounded-full -translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-16 translate-y-16"></div>
        <div class="relative text-center max-w-md">
            <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                <i class="fa-solid fa-graduation-cap text-5xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3">Smart School</h1>
            <p class="text-blue-200 text-lg leading-relaxed">Sistem Informasi Sekolah Terintegrasi untuk Akademik, Konseling, dan Monitoring Orang Tua</p>
            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <i class="fa-solid fa-users text-2xl mb-2 block"></i>
                    <p class="text-xs font-semibold">Multi Role</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <i class="fa-solid fa-shield-halved text-2xl mb-2 block"></i>
                    <p class="text-xs font-semibold">Aman & Terpercaya</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <i class="fa-solid fa-chart-line text-2xl mb-2 block"></i>
                    <p class="text-xs font-semibold">Monitoring Real-time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="w-full lg:w-auto lg:min-w-[440px] flex items-center justify-center p-6 bg-white/5 lg:bg-white/10 backdrop-blur-sm">
        <div class="w-full max-w-sm">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-graduation-cap text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Smart School</h1>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <div class="mb-7">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang!</h2>
                    <p class="text-gray-500 text-sm mt-1">Masuk ke sistem Smart School</p>
                </div>

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-envelope text-gray-400 mr-1"></i> Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 focus:bg-white text-sm"
                            placeholder="email@smartschool.id">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-lock text-gray-400 mr-1"></i> Password
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 focus:bg-white text-sm"
                            placeholder="••••••••">
                    </div>
                    <button type="submit"
                        class="w-full bg-primary text-white py-3.5 rounded-xl font-bold hover:bg-secondary transition-all duration-200 shadow-lg hover:shadow-secondary/30 text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Masuk ke Sistem
                    </button>
                </form>

                <!-- Info Password Demo -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 text-center">Password akun demo: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">password</code></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
