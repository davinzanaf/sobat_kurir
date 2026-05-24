<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sobat Kurir')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .subtle-grid {
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, 0.045) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.045) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    @php
        $role = session('role');
        $namaUser = session('nama_lengkap', 'User');

        $dashboardUrl = match ($role) {
            'admin' => route('admin.dashboard'),
            'kurir' => route('kurir.dashboard'),
            'customer' => route('customer.dashboard'),
            default => url('/'),
        };
    @endphp

    <div class="min-h-screen flex flex-col">
        <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
            <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0h7.5m-10.5 0H3.75a.75.75 0 01-.75-.75V6.75A.75.75 0 013.75 6h10.5a.75.75 0 01.75.75v12m0 0h.75m0 0a1.5 1.5 0 003 0m-3 0a1.5 1.5 0 013 0m0 0h1.5a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.22-.53l-2.25-2.25a.75.75 0 00-.53-.22H15" />
                        </svg>
                    </div>

                    <div>
                        <span class="block text-lg font-extrabold tracking-tight text-slate-950">
                            Sobat Kurir
                        </span>
                        <span class="hidden text-xs font-medium text-slate-500 sm:block">
                            Local Delivery Service
                        </span>
                    </div>
                </a>

                @if(!$role)
                    <div class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
                        <a href="{{ url('/') }}" class="transition hover:text-blue-600">Beranda</a>
                        <a href="{{ route('customer.cek-ongkir') }}" class="transition hover:text-blue-600">Cek Ongkir</a>
                        <a href="{{ route('customer.tracking') }}" class="transition hover:text-blue-600">Lacak Paket</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('kurir.daftar') }}" class="hidden text-sm font-bold text-slate-600 transition hover:text-blue-600 sm:inline-flex">
                            Daftar Kurir
                        </a>

                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                            Login
                        </a>
                    </div>
                @else
                    <div class="hidden items-center gap-7 text-sm font-semibold text-slate-600 lg:flex">
                        <a href="{{ $dashboardUrl }}" class="transition hover:text-blue-600">Dashboard</a>

                        @if($role === 'admin')
                            <a href="{{ route('admin.kurir.index') }}" class="transition hover:text-blue-600">Kurir</a>
                            <a href="{{ route('admin.kurir.pendaftar') }}" class="transition hover:text-blue-600">Pendaftar</a>
                            <a href="{{ route('admin.tarif.index') }}" class="transition hover:text-blue-600">Tarif</a>
                            <a href="{{ route('admin.pesanan.index') }}" class="transition hover:text-blue-600">Pesanan</a>
                        @elseif($role === 'kurir')
                            <a href="{{ route('kurir.tugas-baru') }}" class="transition hover:text-blue-600">Tugas Baru</a>
                            <a href="{{ route('kurir.pesanan-saya') }}" class="transition hover:text-blue-600">Pesanan Saya</a>
                        @elseif($role === 'customer')
                            <a href="{{ route('customer.pesanan.create') }}" class="transition hover:text-blue-600">Buat Pesanan</a>
                            <a href="{{ route('customer.riwayat-pesanan') }}" class="transition hover:text-blue-600">Riwayat</a>
                            <a href="{{ route('customer.tracking') }}" class="transition hover:text-blue-600">Tracking</a>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="max-w-[160px] truncate text-sm font-extrabold text-slate-950">
                                {{ $namaUser }}
                            </p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                {{ $role }}
                            </p>
                        </div>

                        <a href="{{ $dashboardUrl }}" class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600 md:inline-flex">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-extrabold text-white transition hover:bg-slate-800">
                                Logout
                            </button>
                        </form>
                    </div>
                @endif
            </nav>

            @if($role)
                <div class="border-t border-slate-100 bg-white px-5 py-3 lg:hidden">
                    <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto text-sm font-bold text-slate-600">
                        <a href="{{ $dashboardUrl }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Dashboard</a>

                        @if($role === 'admin')
                            <a href="{{ route('admin.kurir.index') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Kurir</a>
                            <a href="{{ route('admin.kurir.pendaftar') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Pendaftar</a>
                            <a href="{{ route('admin.tarif.index') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Tarif</a>
                            <a href="{{ route('admin.pesanan.index') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Pesanan</a>
                        @elseif($role === 'kurir')
                            <a href="{{ route('kurir.tugas-baru') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Tugas Baru</a>
                            <a href="{{ route('kurir.pesanan-saya') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Pesanan Saya</a>
                        @elseif($role === 'customer')
                            <a href="{{ route('customer.pesanan.create') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Buat Pesanan</a>
                            <a href="{{ route('customer.riwayat-pesanan') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Riwayat</a>
                            <a href="{{ route('customer.tracking') }}" class="shrink-0 rounded-xl bg-slate-100 px-4 py-2">Tracking</a>
                        @endif
                    </div>
                </div>
            @endif
        </header>

        <main class="flex-1">
            @if($errors->any())
                <div class="mx-auto mt-6 max-w-7xl px-5 lg:px-8">
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mx-auto mt-6 max-w-7xl px-5 lg:px-8">
                    <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white px-5 py-8 lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col justify-between gap-4 text-sm text-slate-500 md:flex-row md:items-center">
                <p>© {{ date('Y') }} Sobat Kurir. Semua hak dilindungi.</p>

                <div class="flex flex-wrap gap-5">
                    <a href="{{ route('customer.cek-ongkir') }}" class="hover:text-blue-600">Cek Ongkir</a>
                    <a href="{{ route('customer.tracking') }}" class="hover:text-blue-600">Tracking</a>
                    <a href="{{ route('kurir.daftar') }}" class="hover:text-blue-600">Daftar Kurir</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
