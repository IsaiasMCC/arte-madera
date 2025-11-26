<?php

use App\Http\Controllers\AccesibilidadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\PagoFacilController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthCheckCuston;
use App\Http\Middleware\AuthNotCheck;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'index'])->name('auth.index')->middleware([AuthNotCheck::class]);
Route::post('/login', [AuthController::class, 'store'])->name('auth.store')->middleware([AuthNotCheck::class]);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form')->middleware([AuthNotCheck::class]);
Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware([AuthNotCheck::class]);
Route::middleware([AuthCheckCuston::class])->group(function () {
    Route::get('/', function () {
        return redirect('/home');
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::resource('roles', RoleController::class);
    Route::patch('/roles/permisos/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
    Route::resource('usuarios', UserController::class);

    Route::get('/tienda-madera', [TiendaController::class, 'tienda'])->name('tiendas.tienda');
    Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/remover/{id}', [CarritoController::class, 'remover'])->name('carrito.remover');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/envio', [CheckoutController::class, 'guardarEnvio'])->name('checkout.guardarEnvio');
    Route::get('/checkout/pagar/{pedido}', [CheckoutController::class, 'mostrarPago'])->name('checkout.pagar');
    Route::post('/checkout/procesar/{pedido}', [CheckoutController::class, 'procesarPago'])->name('checkout.procesar');
    Route::post('/checkout/procesar/detalle/{pago}', [CheckoutController::class, 'procesarPagoDetalle'])->name('checkout.procesar_detalle');
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('pedidos.mios');
    Route::get('/mis-pedidos/{id}/estado', [PedidoController::class, 'estado'])->name('pedidos.estado');


    Route::get('/pedido/{id}', [PedidoController::class, 'seguimiento'])->name('pedido.seguimiento');

    Route::resource('categorias', CategoriaController::class);
    Route::resource('tiendas', TiendaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('metodos_pago', MetodoPagoController::class);
    Route::resource('pedidos', PedidoController::class);
    Route::resource('envios', EnvioController::class);

    //theme
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/theme/{theme}', [ThemeController::class, 'setTheme'])->name('setTheme');
    Route::get('/mode/{mode}', [ThemeController::class, 'setMode'])->name('setMode');
    Route::get('/accessibility/{option}', [ThemeController::class, 'setAccessibility'])->name('setAccessibility');

    //Accesibilidad
    Route::get('accesibilidad', [AccesibilidadController::class, 'accesibilidad'])->name('accesibilidad');
    //Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');

        Route::get('/pedidos/reporte', [ReporteController::class, 'pedidosPorFecha'])->name('reportes.pedidos');
        Route::get('/envios/reporte', [ReporteController::class, 'enviosPorEstado'])->name('reportes.envios');
        Route::get('/productos/reporte', [ReporteController::class, 'productosMasVendidos'])->name('reportes.productos');
        Route::get('ventas/reporte', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    });

    Route::post('/pagofacil/generar-qr', [PagoFacilController::class, 'generarQR']);
    Route::post('/pagofacil/consultar-estado', [PagoFacilController::class, 'consultarEstado']);
    Route::post('/pagofacil/callback', [PagoFacilController::class, 'urlCallback'])->name('pagofacil.callback');

    Route::get('/test-login-pagofacil', function () {
        $tokenService = "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d504a62547a9de71bfc76be2c2ae01039ebcb0f74a96f0f1f56542c8b51ef7a2a6da9ea16f23e52ecc4485b69640297a5ec6a701498d2f0e1b4e7f4b7803bf5c2eba";
        $tokenSecret = "0C351C6679844041AA31AF9C";

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'tcTokenService' => $tokenService,
            'tcTokenSecret' => $tokenSecret,
            'Content-Type' => 'application/json'
        ])->post('https://masterqr.pagofacil.com.bo/api/services/v2/login');

        return response()->json([
            'status' => $response->status(),
            'body' => $response->json(),
            'headers' => $response->headers()
        ]);
    });
});
