<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConsultaPublicaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Zona publica (RF-04: consulta por DNI sin login).
Route::get('/', [ConsultaPublicaController::class, 'formulario'])->name('inicio');
Route::get('/consulta', [ConsultaPublicaController::class, 'formulario'])->name('consulta.formulario');
Route::post('/consulta', [ConsultaPublicaController::class, 'buscar'])->name('consulta.buscar');

// Autenticacion.
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Panel interno (auth requerido).
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Ordenes de trabajo 
    Route::get('/ordenes', [OrdenTrabajoController::class, 'index'])->name('ordenes.index');
    Route::get('/ordenes/crear', [OrdenTrabajoController::class, 'create'])->name('ordenes.create');
    Route::post('/ordenes', [OrdenTrabajoController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/{orden}', [OrdenTrabajoController::class, 'show'])->name('ordenes.show');
    Route::get('/ordenes/{orden}/editar', [OrdenTrabajoController::class, 'edit'])->name('ordenes.edit');
    Route::put('/ordenes/{orden}', [OrdenTrabajoController::class, 'update'])->name('ordenes.update');
    Route::post('/ordenes/{orden}/items', [OrdenTrabajoController::class, 'agregarItem'])->name('ordenes.items.store');
    Route::delete('/ordenes/{orden}/items/{item}', [OrdenTrabajoController::class, 'quitarItem'])->name('ordenes.items.destroy');
    Route::post('/ordenes/{orden}/pagos', [OrdenTrabajoController::class, 'registrarPago'])->name('ordenes.pagos.store');

    // Comprobante interno imprimible.
    Route::get('/ordenes/{orden}/comprobante', [TicketController::class, 'ver'])->name('tickets.ver');
    Route::get('/ordenes/{orden}/comprobante/pdf', [TicketController::class, 'descargar'])->name('tickets.descargar');

    // Clientes.
    Route::resource('clientes', ClienteController::class)->except(['destroy']);

    // Catalogo 
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::middleware('role:admin')->group(function (): void {
        Route::get('/catalogo/crear', [CatalogoController::class, 'create'])->name('catalogo.create');
        Route::post('/catalogo', [CatalogoController::class, 'store'])->name('catalogo.store');
        Route::get('/catalogo/{item}/editar', [CatalogoController::class, 'edit'])->name('catalogo.edit');
        Route::put('/catalogo/{item}', [CatalogoController::class, 'update'])->name('catalogo.update');
    });

    // Inventario 
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');

    // Caja diaria 
    Route::get('/caja', CajaController::class)->name('caja.index');

    // Usuarios 
    Route::middleware('role:admin')->group(function (): void {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    });
});
