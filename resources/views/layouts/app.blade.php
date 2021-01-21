<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .nav-item {
            color: white !important;
            font-weight: bold;
            opacity: 1 !important;
        }

        .nav-link {
            color: white !important;
            font-weight: bold;
            font-size: 1rem;
            opacity: 0.7;
        }

        .nombre_sistema {
            border-left: solid 1.5px rgba(255, 255, 255, 0.8);
            padding-left: 1rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-left: 1rem;
        }

        .active {
            opacity: 1;
            font-weight: bolder;
        }

        .active::after {
            content: '';
            text-align: center;
            width: 100%;
            height: 2px;
            margin: auto;
            background-color: white;
            border-radius: 5px;
            display: block;
        }

        .btn {
            font-weight: bold;
        }
    </style>

    @stack('head')
</head>

<body>
    <div id="app">
        <nav style="background-color: #781617 !important" class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img style="max-width: 3rem"
                        src="https://upload.wikimedia.org/wikipedia/commons/9/93/Escudo_de_la_Universidad_T%C3%A9cnica_de_Ambato.png"
                        class="logo" alt="" srcset="">

                    <span class="nombre_sistema"> {{ config('app.name', 'Laravel') }}<span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        @can('ADMINISTRADOR')
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('formatos')) ? 'active' : '' }}"
                                    href="/formatos">Formatos</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('carreras')) ? 'active' : '' }}"
                                    href="/carreras">Carreras</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('usuarios')) ? 'active' : '' }}"
                                    href="/usuarios">Usuarios</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('estudiantes')) ? 'active' : '' }}"
                                    href="/estudiantes">Estudiantes</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('configuraciones')) ? 'active' : '' }}"
                                    href="/configuraciones">Configuración</a>
                            </li>
                        @endcan

                        @can('ABOGADO')
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('consejos')) ? 'active' : '' }}"
                                    href="/consejos">Consejos</a>
                            </li>
                        @endcan

                        @can('AYUDANTE')
                            <li class="nav-item ">
                                <a class="nav-link {{ (request()->is('consejos')) ? 'active' : '' }}"
                                    href="/consejos">Consejos</a>
                            </li>
                        @endcan

                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Cerrar Sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>


    </div>
</body>

</html>