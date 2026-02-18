<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <link rel="icon" type="image/png" href="{{ asset('siloam.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <!-- BRAND / HOME -->
                <a class="navbar-brand" href="{{ url('/') }}" title="Home">
                    <i class="fa-solid fa-house fa-lg"></i>
                </a>

                <!-- TOGGLER MOBILE -->
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- LEFT MENU -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- RIGHT MENU -->
                    <ul class="navbar-nav align-items-center gap-2">

                        <!-- LOGIN (GUEST) -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"
                                title="Login">
                                    <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                                </a>
                            </li>
                        @else

                            <!-- PRODUCT -->
                            @can('product-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}"
                                title="Products">
                                    <i class="fa-solid fa-box fa-lg"></i>
                                </a>
                            </li>
                            @endcan

                            <!-- FINANCE -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown"
                                title="Finance">
                                    <i class="fa-solid fa-book fa-lg"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                
                                    @can('hardcopy-list')
                                    <a class="dropdown-item" href="{{ route('hardcopys.index') }}">                                        
                                        <i class="fa-solid fa-book-bookmark fa-lg"></i> Hard Copy
                                    </a>
                                    @endcan       
                                </div>
                            </li>



                            <!-- USER ACCESS -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown"
                                title="User Access">
                                    <i class="fa-solid fa-gear fa-lg"></i>
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

                            <!-- USER PROFILE -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#"
                                    data-bs-toggle="dropdown"
                                    title="{{ Auth::user()->name }}">
                                        <i class="fa-solid fa-user-circle fa-lg"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item"
                                        href="{{ route('users.change', Auth::id()) }}">
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





        <main class="py-4">
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