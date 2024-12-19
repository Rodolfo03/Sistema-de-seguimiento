<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /*return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);*/
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    $equiposPorEstado = \App\Models\Equipo::select('estado', DB::raw('count(*) as total'))
        ->groupBy('estado')
        ->get();
    
    $equiposPorFecha = \App\Models\Equipo::selectRaw('DATE(fecha_ingreso) as fecha, count(*) as total')
        ->groupBy('fecha_ingreso')
        ->get();

    

    return Inertia::render('Dashboard', [
        'clientes' => \App\Models\Cliente::with('cliente:cliente_id')->get(),
        'equipos' => \App\Models\Equipo::with(['cliente', 'servicios', 'user'])->get(),
        'users' => \App\Models\User::all(),
        'equiposPorEstado' => $equiposPorEstado, // Datos agrupados por estado
        'equiposPorFecha' => $equiposPorFecha,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('equipos', App\Http\Controllers\EquipoController::class);
    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
    Route::delete('/equipos/{id}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::get('/equipos/{id}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::delete('/equipos/{id}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::put('/equipos/{id}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{id}', [EquipoController::class, 'destroy'])->name('equipos.destroy');

    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('cliente.destroy');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('cliente.update');

    Route::get('/crearequipo', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');

    Route::get('/crearcliente', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');


});

require __DIR__.'/auth.php';
