<!-- resources/views/layouts/tienda.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tienda de Arte en Madera')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f1e3;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: #8B5E3C;
            /* tono madera */
        }

        .navbar a,
        .navbar-brand {
            color: #fff;
        }

        /* Cards de productos */
        .product-card {
            border: 1px solid #d1bfa7;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            background-color: #fffaf0;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        /* Botón agregar */
        .btn-wood {
            background-color: #A9746E;
            color: white;
        }

        .btn-wood:hover {
            background-color: #8B5E3C;
        }

        /* Carrito */
        .cart-badge {
            position: absolute;
            top: 5px;
            /* right: 50px; */
            font-size: 0.8rem;
            background: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
        }

        /* Footer */
        footer {
            background-color: #8B5E3C;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-5">
        <a class="navbar-brand" href="{{ route('tiendas.tienda') }}">Arte Madera</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Carrito -->
                <li class="nav-item position-relative me-3">
                    <a class="nav-link" href="{{ route('carrito.index') }}">
                        <i class="fa fa-shopping-cart"></i> Carrito
                        <span class="cart-badge"
                            id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                    </a>
                </li>

                <!-- Mis pedidos -->
                <li class="nav-item me-3">
                    <a class="nav-link" href="{{ route('pedidos.mios') }}">
                        <i class="fa fa-box"></i> Mis pedidos
                    </a>
                </li>

                @auth
                    <!-- Usuario autenticado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="fa fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-user"></i> Perfil
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form action="{{ route('auth.logout') }}" method="GET">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="fa fa-sign-out-alt"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <!-- Si NO ha iniciado sesión -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.index') }}">
                            <i class="fa fa-sign-in-alt"></i> Iniciar sesión
                        </a>
                    </li>
                @endguest

            </ul>
        </div>
    </nav>


    <div class="container my-5">
        @yield('content')
    </div>

    <footer class="text-center">
        &copy; {{ date('Y') }} Tienda de Arte en Madera. Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
