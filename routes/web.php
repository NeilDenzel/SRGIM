<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\HistorialController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/usuarios/create', [UsuarioController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');

    Route::get('/catalogos', [ProductoController::class, 'index'])->name('catalogos.index');
    Route::get('/catalogos/create', [ProductoController::class, 'create'])->name('catalogos.create');
    Route::post('/catalogos', [ProductoController::class, 'store'])->name('catalogos.store');
    Route::get('/catalogos/{producto}/edit', [ProductoController::class, 'edit'])->name('catalogos.edit');
    Route::put('/catalogos/{producto}', [ProductoController::class, 'update'])->name('catalogos.update');
    Route::delete('/catalogos/{producto}', [ProductoController::class, 'destroy'])->name('catalogos.destroy');

    Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
    Route::get('/ingresos/create', [IngresoController::class, 'create'])->name('ingresos.create');
    Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::get('/ingresos/{ingreso}', [IngresoController::class, 'show'])->name('ingresos.show');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');

    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::get('/historial', [HistorialController::class, 'index'])->name('historial.index');
});
