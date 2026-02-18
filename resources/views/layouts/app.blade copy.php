<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
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
                <a class="navbar-brand" href="{{ url('/') }}">
                    Home
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar me-auto -->
                    <ul class="navbar-nav "></ul>

                    <!-- Right Side Of Navbar ms-auto -->
                    <ul class="navbar-nav ">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                           
                        @else
                            {{-- 
                            @can('permission-list')
                                <li><a class="nav-link" href="{{ route('permissions.index') }}">Manage Permission</a></li>
                            @endcan
                            @can('role-list')
                                <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                            @endcan
                            @can('user-list')
                                <li><a class="nav-link" href="{{ route('users.index') }}">Manage User</a></li>
                            @endcan
                            --}}
                            @can('product-list')
                                <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            @endcan
                            

                            <div class="collapse navbar-collapse d-flex">
                                <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                       User Akses
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @can('permission-list')
                                        <a class="dropdown-item" href="{{  route('permissions.index') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('permission-form').submit();">
                                            {{ __('Manage Permission') }}
                                        </a>                                                                                

                                        <form id="permission-form" action="{{  route('permissions.index') }}" method="GET" class="d-none">
                                            @csrf
                                        </form>
                                        @endcan 

                                        @can('role-list')
                                        <a class="dropdown-item" href="{{  route('roles.index') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('role-form').submit();">
                                            {{ __('Manage Role') }}
                                        </a>                                                                                

                                        <form id="role-form" action="{{  route('roles.index') }}" method="GET" class="d-none">
                                            @csrf
                                        </form>
                                        @endcan                                       
                                        
                                        
                                        @can('user-list')
                                        <a class="dropdown-item" href="{{  route('users.index') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('user-form').submit();">
                                            {{ __('Manage User') }}
                                        </a>                                                                                

                                        <form id="user-form" action="{{  route('users.index') }}" method="GET" class="d-none">
                                            @csrf
                                        </form>
                                        @endcan
                                      
                                       

                                    </div>

                                    
                                       

                                    
                                </li>
                                </ul>
                            </div>



                            <div class="collapse navbar-collapse d-flex">
                                <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{  route('users.change', Auth::user()->id) }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('chg-form').submit();">
                                            {{ __('Change Password') }}
                                        </a>                                                                                

                                        <form id="chg-form" action="{{  route('users.change', Auth::user()->id) }}" method="GET" class="d-none">
                                            @csrf
                                        </form>

                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>                                                                                

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>

                                    </div>

                                    
                                       

                                    
                                </li>
                                </ul>
                            </div>

                            
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