<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@stack('title')</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> {{-- no borrar --}}

    {{-- AGREGADOS NUEVOS QUE TENIAN EN COMUN CON OTRAS VISTAS --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">{{-- no borrar --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')

    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet"> {{-- no borrar --}}
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">{{-- no borrar --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    {{-- ESTILOS DEFINIDOS --}}
    @if (session('mode') == 'adults')
        <link rel="stylesheet" href="{{ asset('css/theme/adults.css') }}" id="theme-style">
    @elseif(session('mode') == 'teens')
        <link rel="stylesheet" href="{{ asset('css/theme/teens.css') }}" id="theme-style">
    @elseif(session('mode') == 'kids')
        <link rel="stylesheet" href="{{ asset('css/theme/kids.css') }}" id="theme-style">
    @elseif (session('mode') == 'night')
        <link rel="stylesheet" href="{{ asset('css/theme/night.css') }}" id="theme-style">
    @else
        <link rel="stylesheet" href="{{ asset('css/theme/day.css') }}" id="theme-style">
    @endif

    <script>
        (function() {
            const fontSize = localStorage.getItem('fontSize');
            if (fontSize) {
                document.documentElement.style.fontSize = fontSize;
            }
        })();
    </script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation" id="menu">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element text-center">
                            <img alt="image" class="rounded-circle" width="100" height="100"
                                src="{{ asset('images/logo-arte.jpg') }}" />
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="block m-t-xs font-bold"
                                    style="word-wrap: break-word; white-space: normal;">
                                    ARTE EN MADERA
                                </span>
                                <span> {{ auth()->user()->nombres }} {{ auth()->user()->apellidos }} </span>
                            </a>

                            {{-- <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="{{ route('setAccessibility', 'large-font') }}">Accesibilidad</a></li>

                                <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                                <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                                <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="login.html">Logout</a></li>

                            </ul> --}}
                        </div>
                        <div class="logo-element">
                            Arte Madera
                        </div>
                    </li>

                    <!-- Menu Items -->
                    @canany(['roles index', 'permisos index', 'usuarios index'])
                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label">Roles y
                                    Permisos</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">

                                @can(['roles index'])
                                    <li><a href="{{ route('roles.index') }}">Roles</a></li>
                                @endcan

                                @can(['usuarios index'])
                                    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcanany

                    @canany(['tiendas index', 'productos index'])

                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label">Gestionar Tiendas y
                                    Productos </span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">

                                @can(['tiendas index'])
                                    <li><a href="{{ route('tiendas.index') }}">Tiendas</a></li>
                                @endcan

                                @can(['productos index'])
                                    <li><a href="{{ route('productos.index') }}">Productos</a></li>
                                @endcan
                            </ul>
                        </li>

                    @endcanany


                    @canany(['metodos_pago index', 'categorias index'])
                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label">Gestionar Categorias y
                                    Metodos </span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                @can(['metodos_pago index'])
                                    <li><a href="{{ route('metodos_pago.index') }}">Metodo Pagos</a></li>
                                @endcan
                                @can(['categorias index'])
                                    <li><a href="{{ route('categorias.index') }}">Categorias</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['envios index', 'pedidos index'])
                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label"> Gestionar Envios
                                </span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">

                                @can(['envios index'])
                                    <li><a href="{{ route('envios.index') }}"> Envios </a></li>
                                @endcan
                                @can(['pedidos index'])
                                    <li><a href="{{ route('pedidos.index') }}"> Pedidos </a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcanany

                    @canany(['pagos index'])
                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label"> Gestionar Pagos
                                </span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">

                                @can(['envios index'])
                                    <li><a href="{{ route('envios.index') }}"> Pagos </a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcanany

                    @canany(['reportes envios', 'reportes pedidos', 'reportes productos', 'reportes ventas'])
                        <li>
                            <a href="#"> <i class="fa fa-user"></i> <span class="nav-label"> Gestionar Reportes
                                </span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">

                                @can(['reportes envios'])
                                    <li><a href="{{ route('reportes.envios') }}"> Envios </a></li>
                                @endcan
                                @can(['reportes pedidos'])
                                    <li><a href="{{ route('reportes.pedidos') }}"> Pedidos </a></li>
                                @endcan
                                @can(['reportes productos'])
                                    <li><a href="{{ route('reportes.productos') }}"> Productos </a></li>
                                @endcan
                                @can(['reportes ventas'])
                                    <li><a href="{{ route('reportes.ventas') }}"> Ventas </a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcanany

                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i> </a>
                        {{-- <form role="search" class="navbar-form-custom" action="{{ route('search') }}" method="GET">
                            <div class="form-group">
                                <input type="text" placeholder="Buscar por página..."
                                    class="form-control custom-search-input" name="query" id="query">
                            </div>
                        </form> --}}
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="modo-style">
                            <?php
                            $modeText = session('mode') == 'teens' ? 'Joven' : (session('mode') == 'night' ? 'Noche' : (session('mode') == 'adults' ? 'Adulto' : (session('mode') == 'kids' ? 'Niño' : 'Día')));
                            ?>
                            <span class="m-r-sm welcome-message" id="modoTema">Modo {{ $modeText }}</span>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                Temas
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="{{ route('setMode', 'days') }}" class="dropdown-item">
                                        <div>
                                            <svg class="fa fa-fw" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5 17.5L19 19M20 12H22M6.5 6.5L5 5M17.5 6.5L19 5M6.5 17.5L5 19M2 12H4M12 2V4M12 20V22M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12Z"
                                                    stroke="#000000" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg> Día
                                            {{-- <span class="float-right text-muted small">4 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('setMode', 'night') }}" class="dropdown-item">
                                        <div>
                                            <svg class="fa fa-fw" viewBox="0 0 36 36" version="1.1"
                                                preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>moon-solid</title>
                                                <path
                                                    d="M29.2,26.72A12.07,12.07,0,0,1,22.9,4.44,13.68,13.68,0,0,0,19.49,4a14,14,0,0,0,0,28,13.82,13.82,0,0,0,10.9-5.34A11.71,11.71,0,0,1,29.2,26.72Z"
                                                    class="clr-i-solid clr-i-solid-path-1"></path>
                                                <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                                            </svg> Noche
                                            {{-- <span class="float-right text-muted small">4 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('setMode', 'kids') }}" class="dropdown-item">
                                        <div>
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                class="fa fa-fw" viewBox="0 0 300 300"
                                                enable-background="new 0 0 300 300" xml:space="preserve">
                                                <path d="M213,163v-48l8.2-2.8l29.1,37.8L213,163z M176.5,55.5c0-25.8-20.9-46.7-46.7-46.7S83.1,29.7,83.1,55.5s20.9,46.7,46.7,46.7
 C155.6,102.3,176.5,81.3,176.5,55.5z M203.7,135.5c-2.4-9.9-12.4-16-22.4-13.5l-35.1,8.6c0,0-47-28.4-47.8-28.8
 c-16.9-7.7-37.2-1.1-46.3,15.4l-34.3,62.4c-6.9,12.6-5.5,27.5,2.4,38.4c0.2,0.3,30.4,34.9,30.4,34.9H27.5
 c-11.4,0-20.4,9.7-19.4,21.3C9,284.4,17.8,292,28,292h66.5c5.7,0,14.5-2.6,18.7-12c4-8.8,0.6-17.4-4.5-23l-31.5-36.1l36.7-66.7
 l19.8,12c3.7,2.2,9.6,3.2,14,2.1c10.7-2.5,42.5-10.4,42.5-10.4C200.1,155.5,206.1,145.5,203.7,135.5z M268.5,222l-7.6-23H214
 l-7.6,23H268.5z M272.5,234h-70.1l-7.6,23h85.4L272.5,234z M284.1,269h-93.4l-7.6,23h108.7L284.1,269z" />
                                            </svg> Niño
                                            {{-- <span class="float-right text-muted small">4 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('setMode', 'teens') }}" class="dropdown-item">
                                        <div>
                                            <svg class="fa fa-fw" version="1.1" id="Capa_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 23.838 23.838"
                                                style="enable-background:new 0 0 23.838 23.838;" xml:space="preserve">
                                                <g>
                                                    <path d="M11.92,0C5.717,0,0.672,3.98,0.672,11.038c0,7.057,5.045,12.8,11.248,12.8s11.247-5.743,11.247-12.8
  C23.167,3.98,18.123,0,11.92,0z M5.554,11.038l4.402-3.274l-1.761,3.303c0,0,8.721,1.813,13.442-0.028
  c0,0.576-0.039,1.143-0.113,1.695H2.315c-0.073-0.553-0.114-1.119-0.114-1.695L5.554,11.038L5.554,11.038z M11.92,22.098
  c-4.386,0-8.092-3.305-9.301-7.847h2.905c0,1.588,1.288,2.879,2.877,2.879c1.591,0,2.879-1.291,2.879-2.879h1.325
  c0.084,1.513,1.329,2.718,2.863,2.718s2.776-1.205,2.861-2.718h2.89C20.01,18.793,16.305,22.098,11.92,22.098z M13.794,19.627
  c-2.315,1.172-3.7,0.032-3.715,0.02C10,19.581,9.891,19.606,9.835,19.704c-0.055,0.098-0.033,0.23,0.046,0.298
  c0.039,0.036,0.706,0.591,1.884,0.591c0.593,0,1.32-0.143,2.161-0.565c0.089-0.049,0.133-0.173,0.095-0.282
  C13.984,19.635,13.882,19.58,13.794,19.627z" />
                                                </g>
                                            </svg> Joven
                                            {{-- <span class="float-right text-muted small">12 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('setMode', 'adults') }}" class="dropdown-item">
                                        <div>
                                            <svg class="fa fa-fw" viewBox="-17 0 512 512" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill="#000000"
                                                    d="M341 138C341 67.3075 300.856 16 239 16C177.144 16 137 67.3075 137 138C137 231.394 189 221.676 189 269C189 287.649 139 288 139 288C59.471 288 0 352.471 0 432V464C0 490.51 21.4903 512 48 512H431C457.51 512 479 490.51 479 464V432C479 352.471 418.529 288 339 288C339 288 289 287.649 289 269C289 221.676 341 231.394 341 138Z">
                                                </path>
                                            </svg> Adulto
                                            {{-- <span class="float-right text-muted small">4 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('accesibilidad') }}" class="dropdown-item">
                                        <div>
                                            Accesibilidad
                                            {{-- <span class="float-right text-muted small">4 minutes ago</span> --}}
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" href="{{ route('home') }}"> Tablero </a>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html" class="dropdown-item">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="float-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="profile.html" class="dropdown-item">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="float-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="grid_options.html" class="dropdown-item">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="float-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html" class="dropdown-item">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>

                            </ul>
                        </li>


                        <li>
                            <a href="{{ route('auth.logout') }}" class="sign-out">
                                <i class="fa fa-sign-out"></i> Salir
                            </a>
                        </li>
                        {{-- <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li> --}}
                    </ul>
                </nav>
            </div>
            <div>
                @yield('static')
            </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2
                        class="font-title-style {{ session('mode') == 'kids' ? 'animate__animated animate__bounce' : '' }}">
                        @yield('content_header')
                    </h2>
                </div>
            </div>

            <div
                class="wrapper wrapper-content custom-content-style my-4 {{ session('mode') == 'kids' ? 'animate__animated animate__fadeInLeft' : '' }}">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/popper.min.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/bootstrap.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/inspinia.js') }}"></script>{{-- no borrar --}}
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script> {{-- no borrar --}}

    {{-- AGREGADOS NUEVOS QUE TENIAN EN COMUN CON OTRAS VISTAS --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>{{-- no borrar --}}
    @stack('scripts')
</body>
<footer class="footer" style="background: white">
    <p>Visitas a esta página: {{ $pageVisits }}</p>
</footer>

</html>
