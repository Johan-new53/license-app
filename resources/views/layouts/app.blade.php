<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="icon" type="image/png" href="{{ asset('siloam.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            min-height: 100vh;
        }

        /* ── Navbar base ── */
        #main-navbar {
            background: linear-gradient(135deg, #001f5c 0%, #003d8a 55%, #0066b3 100%) !important;
            box-shadow: 0 2px 16px rgba(0,31,92,0.45);
            padding: 0 0;
        }
        #main-navbar .container { gap: 0; min-height: 52px; }

        /* Brand */
        #main-navbar .navbar-brand {
            color: #fff !important;
            font-size: 1rem;
            font-weight: 600;
            white-space: nowrap;
            padding: 0.45rem 0.75rem 0.45rem 0;
        }
        #main-navbar .navbar-brand img {
            width: 26px; height: 26px;
            border-radius: 6px;
            background: #fff;
            padding: 2px;
            object-fit: contain;
        }

        /* nav links */
        #main-navbar .nav-link {
            color: rgba(255,255,255,0.80) !important;
            border-radius: 8px;
            padding: 0.38rem 0.6rem !important;
            font-size: 0.82rem;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        #main-navbar .nav-link:hover,
        #main-navbar .nav-link.show {
            color: #fff !important;
            background: rgba(255,255,255,0.12);
        }

        /* App section labels in navbar */
        .nav-app-label {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
        }
        .nav-app-label.license {
            color: rgba(167,243,208,0.9);   /* green tint */
            background: rgba(52,211,153,0.10);
        }
        .nav-app-label.prf {
            color: rgba(196,181,253,0.9);   /* purple tint */
            background: rgba(139,92,246,0.10);
        }

        /* Slim vertical divider between app sections */
        .nav-app-divider {
            width: 1px;
            height: 28px;
            background: rgba(255,255,255,0.15);
            margin: 0 0.4rem;
            align-self: center;
            flex-shrink: 0;
        }

        /* Dropdown */
        #main-navbar .dropdown-menu {
            background: #002b6e;
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.35);
            padding: 0.45rem;
            min-width: 210px;
        }
        .dropdown-section-header {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 0.3rem 0.75rem 0.1rem;
            pointer-events: none;
        }
        #main-navbar .dropdown-item {
            color: rgba(255,255,255,0.80);
            border-radius: 9px;
            font-size: 0.875rem;
            padding: 0.48rem 0.85rem;
            transition: all 0.15s ease;
        }
        #main-navbar .dropdown-item:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        #main-navbar .dropdown-item.text-danger { color: rgba(252,165,165,0.9) !important; }
        #main-navbar .dropdown-item.text-danger:hover { background: rgba(239,68,68,0.15) !important; }

        #main-navbar .navbar-toggler { border: 1px solid rgba(255,255,255,0.25); }
        #main-navbar .navbar-toggler-icon { filter: invert(1); }

        /* ── Main content ── */
        main { padding: 1.75rem 0; }
        main > .container > .row > .col-md-12 > .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        main > .container > .row > .col-md-12 > .card > .card-body { padding: 1.75rem; }

        /* ── General polish ── */
        .card { border-radius: 12px; }
        .btn { border-radius: 8px; }
        .form-control, .form-select { border-radius: 8px; }
        table.table thead { background-color: #f7f9fc; }
    </style>
</head>
<body>
    <div id="app">
        <nav id="main-navbar" class="navbar navbar-expand-md">
            <div class="container">

                <!-- BRAND -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}" title="Home">
                    <img src="{{ asset('siloam.png') }}" alt="Logo">
                    <span>Siloam</span>
                </a>

                <!-- TOGGLER MOBILE -->
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>

                    <ul class="navbar-nav align-items-center">

                        @guest
                            <!-- GUEST: just show login -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" title="Login">
                                    <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                                </a>
                            </li>
                        @else

                            @can('product-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}" title="Products">
                                    <i class="fa-solid fa-box fa-sm me-1"></i> Products
                                </a>
                            </li>
                            @endcan

                            {{-- Divider between apps --}}
                            @can('product-list')
                                @canany(['hardcopy-list','softcopy-list','automate-list','approval-list','payment-list','finance-import'])
                                <li class="nav-item d-none d-md-flex">
                                    <div class="nav-app-divider"></div>
                                </li>
                                @endcanany
                            @endcan

                            <!-- FINANCE -->
                            @canany(['hardcopy-list','softcopy-list','automate-list','digital-list','approval-list','payment-list','finance-import'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                   data-bs-toggle="dropdown" title="Finance">
                                    <i class="fa-solid fa-book fa-sm me-1"></i> Finance
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('hardcopy-list')
                                    <a class="dropdown-item" href="{{ route('hardcopys.index') }}">
                                        <i class="fa-solid fa-book-bookmark me-2"></i> Hard Copy
                                    </a>
                                    @endcan
                                    @can('softcopy-list')
                                    <a class="dropdown-item" href="{{ route('softcopys.index') }}">
                                        <i class="fa-solid fa-file-arrow-up me-2"></i> Soft Copy
                                    </a>
                                    @endcan
                                    @can('automate-list')
                                    <a class="dropdown-item" href="{{ route('automates.index') }}">
                                        <i class="fa-solid fa-robot me-2"></i> Automate
                                    </a>
                                    @endcan
                                     @can('digital-list')
                                    <a class="dropdown-item" href="{{ route('digitals.index') }}">
                                        <i class="fa-solid fa-robot me-2"></i> Digital
                                    </a>
                                    @endcan
                                    @can('approval-list')
                                    <a class="dropdown-item" href="{{ route('approvals.index') }}">
                                        <i class="fa-solid fa-thumbs-up me-2"></i> Approval
                                    </a>
                                    @endcan
                                    @can('payment-list')
                                    <a class="dropdown-item" href="{{ route('payments.index') }}">
                                        <i class="fa-solid fa-credit-card me-2"></i> Payment
                                    </a>
                                    @endcan
                                    @can('finance-import')
                                    <a class="dropdown-item" href="{{ route('import') }}">
                                        <i class="fa-solid fa-file-import me-2"></i> Import
                                    </a>
                                    @endcan
                                </div>
                            </li>
                            @endcanany

                            <!-- MASTER DATA -->
                            @canany(['bank-list','dept-list','reksumber-list','matauang-list','rektujuan-list','category-list','ppn-list'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                   data-bs-toggle="dropdown" title="Master Data">
                                    <i class="fa-solid fa-database fa-sm me-1"></i> Master Data
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('bank-list')
                                    <a class="dropdown-item" href="{{ route('bank.index') }}">
                                        <i class="fa-solid fa-building-columns me-2"></i> Bank
                                    </a>
                                    @endcan
                                    @can('dept-list')
                                    <a class="dropdown-item" href="{{ route('department.index') }}">
                                        <i class="fa-solid fa-building me-2"></i> Department
                                    </a>
                                    @endcan
                                    @can('reksumber-list')
                                    <a class="dropdown-item" href="{{ route('reksumber.index') }}">
                                        <i class="fa-solid fa-wallet me-2"></i> Rekening Sumber
                                    </a>
                                    @endcan
                                    @can('matauang-list')
                                    <a class="dropdown-item" href="{{ route('matauang.index') }}">
                                        <i class="fa-solid fa-coins me-2"></i> Mata Uang
                                    </a>
                                    @endcan
                                    @can('rektujuan-list')
                                    <a class="dropdown-item" href="{{ route('rektujuan.index') }}">
                                        <i class="fa-solid fa-money-bill-transfer me-2"></i> Rekening Tujuan
                                    </a>
                                    @endcan
                                    @can('category-list')
                                    <a class="dropdown-item" href="{{ route('category.index') }}">
                                        <i class="fa-solid fa-list me-2"></i> Category
                                    </a>
                                    @endcan
                                    @can('ppn-list')
                                    <a class="dropdown-item" href="{{ route('ppn.index') }}">
                                        <i class="fa-solid fa-percent me-2"></i> PPN
                                    </a>
                                    @endcan
                                    @can('payable-list')
                                    <a class="dropdown-item" href="{{ route('payable.index', ['type' => 'hardcopy']) }}">
                                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Payable
                                    </a>
                                    @endcan
                                </div>
                            </li>
                            @endcanany

                            {{-- Second divider before settings --}}
                            @canany(['permission-list','role-list','user-list'])
                            <li class="nav-item d-none d-md-flex">
                                <div class="nav-app-divider"></div>
                            </li>
                            @endcanany

                            <!-- USER ACCESS -->
                            @canany(['permission-list','role-list','user-list'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                   data-bs-toggle="dropdown" title="User Access">
                                    <i class="fa-solid fa-gear fa-sm me-1"></i> Admin
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('permission-list')
                                    <a class="dropdown-item" href="{{ route('permissions.index') }}">
                                        <i class="fa-solid fa-key me-2"></i> Permission
                                    </a>
                                    @endcan
                                    @can('role-list')
                                    <a class="dropdown-item" href="{{ route('roles.index') }}">
                                        <i class="fa-solid fa-user-shield me-2"></i> Role
                                    </a>
                                    @endcan
                                    @can('user-list')
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="fa-solid fa-users me-2"></i> User
                                    </a>
                                    @endcan
                                </div>
                            </li>
                            @endcanany

                            {{-- Third divider before profile --}}
                            <li class="nav-item d-none d-md-flex">
                                <div class="nav-app-divider"></div>
                            </li>

                            <!-- USER PROFILE -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#"
                                   data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-circle fa-lg"></i>
                                    <span class="d-none d-lg-inline" style="font-size:0.82rem;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ Auth::user()->name }}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="dropdown-section-header">Akun Saya</div>
                                    <a class="dropdown-item" href="{{ route('users.change', Auth::id()) }}">
                                        <i class="fa-solid fa-key me-2"></i> Change Password
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="dropdown-item text-danger w-100 text-start">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body>
</html>
