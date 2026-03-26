<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} &mdash; Login</title>

    <link rel="icon" type="image/png" href="{{ asset('siloam.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #001240 0%, #002b8a 45%, #0066b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background orbs */
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,102,179,0.25) 0%, transparent 70%);
            top: -200px; left: -200px;
            animation: float1 8s ease-in-out infinite;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,60,140,0.2) 0%, transparent 70%);
            bottom: -150px; right: -150px;
            animation: float2 10s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes float1 {
            0%,100% { transform: translate(0,0); }
            50% { transform: translate(40px, 40px); }
        }
        @keyframes float2 {
            0%,100% { transform: translate(0,0); }
            50% { transform: translate(-40px, -30px); }
        }

        /* White card */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 430px;
            margin: 1.5rem;
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem;
            box-shadow: 0 25px 60px rgba(0,20,80,0.35);
            animation: slideUp 0.5s ease forwards;
        }
        @keyframes slideUp {
            from { opacity:0; transform:translateY(30px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .brand-logo img {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: white;
            padding: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .brand-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #001f5c;
            letter-spacing: -0.3px;
        }
        .login-subtitle {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 2rem;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.25rem 0;
            color: #9ca3af;
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Form labels */
        .form-label {
            color: #374151;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
        }

        /* Inputs */
        .form-control {
            background: #f9fafb !important;
            border: 1px solid #d1d5db !important;
            border-radius: 10px !important;
            color: #111827 !important;
            padding: 0.65rem 1rem !important;
            font-size: 0.9rem !important;
            transition: all 0.2s ease;
        }
        .form-control::placeholder { color: #9ca3af !important; }
        .form-control:focus {
            background: #fff !important;
            border-color: #0066b3 !important;
            box-shadow: 0 0 0 3px rgba(0,102,179,0.15) !important;
            color: #111827 !important;
            outline: none;
        }
        .form-control.is-invalid {
            border-color: #ef4444 !important;
        }
        .invalid-feedback { color: #dc2626; font-size: 0.8rem; }

        /* Input group icon */
        .input-group-icon {
            position: relative;
        }
        .input-group-icon .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.85rem;
            pointer-events: none;
        }
        .input-group-icon .form-control { padding-right: 2.5rem !important; }

        /* Microsoft button */
        .btn-microsoft {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.65rem 1.25rem;
            background: #0066b3;
            border: 1px solid #004f8e;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0,102,179,0.3);
        }
        .btn-microsoft:hover {
            background: #0052a5;
            border-color: #003d8a;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,82,165,0.4);
        }
        .btn-microsoft img { width: 18px; height: 18px; }

        /* Login button */
        .btn-login {
            width: 100%;
            padding: 0.7rem;
            background: linear-gradient(135deg, #0052a5 0%, #0099e6 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0,82,165,0.45);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,82,165,0.55);
            background: linear-gradient(135deg, #003d8a 0%, #0077cc 100%);
        }
        .btn-login:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #9ca3af;
            font-size: 0.78rem;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
