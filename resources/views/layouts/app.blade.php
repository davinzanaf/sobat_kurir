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

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .navbar {
            background: #2563eb;
            color: white;
            padding: 16px 24px;
        }

        .navbar-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-weight: bold;
            font-size: 20px;
            color: white;
            text-decoration: none;
        }

        .nav-links a,
        .nav-links button {
            color: white;
            text-decoration: none;
            margin-left: 14px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .container {
            width: 92%;
            max-width: 1100px;
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
            font-size: 38px;
            margin: 0 0 12px;
            color: #111827;
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
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary {
            background: #111827;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn:hover {
            opacity: 0.92;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            overflow: hidden;
            border-radius: 12px;
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
        }

        .muted {
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 30px;
            }

            .navbar-inner {
                display: block;
            }

            .nav-links {
                margin-top: 12px;
            }

            .nav-links a,
            .nav-links button {
                margin-left: 0;
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="{{ route('home') }}">Sobat Kurir</a>

            <div class="nav-links">
                <a href="{{ route('customer.dashboard') }}">Customer</a>
                <a href="{{ route('customer.tracking') }}">Tracking</a>
                <a href="{{ route('admin.login') }}">Admin</a>
                <a href="{{ route('kurir.login') }}">Kurir</a>

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
