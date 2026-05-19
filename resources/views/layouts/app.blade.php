<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sobat Kurir')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        a {
            color: inherit;
        }

        .navbar {
            background: #2563eb;
            color: white;
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.12);
        }

        .navbar-inner {
            max-width: 1180px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .brand {
            font-weight: bold;
            font-size: 20px;
            color: white;
            text-decoration: none;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-links a,
        .nav-links button {
            color: white;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            transition: 0.2s;
        }

        .nav-links a:hover,
        .nav-links button:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .container {
            width: min(92%, 1180px);
            margin: 32px auto;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            margin-bottom: 20px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 24px;
            align-items: center;
        }

        .hero h1 {
            font-size: clamp(30px, 4vw, 44px);
            margin: 0 0 12px;
            color: #111827;
            line-height: 1.15;
        }

        .hero p {
            color: #4b5563;
            line-height: 1.7;
        }

        .menu {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #2563eb;
            color: white;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            min-height: 42px;
            transition: 0.2s;
        }

        .btn-secondary {
            background: #111827;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .form-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            background: white;
        }

        textarea {
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eff6ff;
            white-space: nowrap;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .muted {
            color: #6b7280;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 992px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }

            .navbar-inner {
                align-items: flex-start;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 14px 16px;
            }

            .navbar-inner {
                flex-direction: column;
            }

            .nav-links {
                width: 100%;
                justify-content: flex-start;
            }

            .nav-links a,
            .nav-links button {
                font-size: 13px;
                padding: 8px 10px;
            }

            .container {
                width: 94%;
                margin: 22px auto;
            }

            .card {
                padding: 18px;
                border-radius: 14px;
            }

            .grid-3,
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .menu {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            table {
                min-width: 680px;
            }
        }

        @media (max-width: 480px) {
            .brand {
                font-size: 18px;
            }

            .nav-links {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .nav-links a,
            .nav-links button {
                width: 100%;
                text-align: center;
            }

            .container {
                width: 95%;
                margin: 18px auto;
            }

            .card {
                padding: 16px;
            }

            input,
            select,
            textarea {
                font-size: 16px;
            }

            table {
                min-width: 640px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="{{ route('home') }}">Sobat Kurir</a>

            <div class="nav-links">
                @if(session('role') === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.kurir.index') }}">Kurir</a>
                    <a href="{{ route('admin.tarif.index') }}">Tarif</a>
                    <a href="{{ route('admin.pesanan.index') }}">Pesanan</a>
                @elseif(session('role') === 'kurir')
                    <a href="{{ route('kurir.dashboard') }}">Dashboard</a>
                    <a href="{{ route('kurir.tugas-baru') }}">Tugas Baru</a>
                    <a href="{{ route('kurir.pesanan-saya') }}">Pesanan Saya</a>
                @elseif(session('role') === 'customer')
                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                    <a href="{{ route('customer.cek-ongkir') }}">Cek Ongkir</a>
                    <a href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
                    <a href="{{ route('customer.tracking') }}">Tracking</a>
                @else
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('customer.cek-ongkir') }}">Cek Ongkir</a>
                    <a href="{{ route('customer.tracking') }}">Tracking</a>
                    <a href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
                    <a href="{{ route('admin.login') }}">Login</a>
                @endif

                @if(session()->has('role'))
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <main class="container">
        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
