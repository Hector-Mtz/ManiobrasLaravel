<?php

use App\Http\Controllers\ListaAsitenciaController;
use App\Http\Controllers\LogActivoController;
use App\Http\Controllers\ManiobraController;
use App\Http\Controllers\ManiobristaController;
use App\Http\Controllers\TurnoController;
use App\Models\Maniobra;
use Illuminate\Foundation\Application;
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
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::controller(ManiobraController::class)->group(
        function () {
            Route::get('/maniobras', 'index')->name('maniobras');
            Route::post('/maniobras', 'store')->name('maniobras.store');
        }
    );

    Route::get('/turnosPorManiobra/{maniobra_id}', [TurnoController::class, 'getTurnos']);
    Route::put('/listaasistencia/{turno}/{maniobrista}/{fecha}', [ListaAsitenciaController::class, 'listaUpdate'])->name('lista.maniobrista.update');
    //Route::apiResource('/listaasistencia', FacturaController::class)->except('show');


    Route::controller(ListaAsitenciaController::class)->group(
        function () {
            Route::post('/lista', 'store')->name('lista.store');
            Route::get('/example', 'exportExample')->name('export');
            Route::get('/downloadReportRh/{lista}', 'exportReporteRh')->name('reportRh');
            Route::get('/consultarReporteRH/{maniobra_id}', 'consultarReporteRH')->name('consultaRh');
        }
    );

    Route::controller(ManiobristaController::class)->group(
        function () {
            Route::get('/maniobristas', 'index')->name('maniobristas');
            Route::post('/maniobristas/{maniobrista_id}', 'update')->name('maniobristas.update');
        }
    );

    Route::controller(TurnoController::class)->group(
        function () {
            Route::get('/turnos', 'index')->name('turnos');
            Route::post('/turnos', 'store')->name('turnos.store');
        }
    );
});


Route::controller(ManiobraController::class)->group(
    function () {
        Route::get('/maniobras', 'index')->name('maniobras');
        Route::post('/maniobras', 'store')->name('maniobras.store');
    }
);

Route::get('/turnosPorManiobra/{maniobra_id}', [TurnoController::class, 'getTurnos']);
Route::put('/listaasistencia/{turno}/{maniobrista}/{fecha}', [ListaAsitenciaController::class, 'listaUpdate'])->name('lista.maniobrista.update');
//Route::apiResource('/listaasistencia', FacturaController::class)->except('show');


Route::controller(ListaAsitenciaController::class)->group(
    function () {
        Route::post('/lista', 'store')->name('lista.store');
        Route::get('/example', 'exportExample')->name('export');
       // Route::get('/downloadReportRh/{lista}', 'exportReporteRh')->name('reportRh');
        Route::get('/consultarReporteRH/{maniobra_id}', 'consultarReporteRH')->name('consultaRh');
        Route::get('/consultaReporteNominas/{maniobra_id}/{fecha_inicial}/{fecha_final}','consultarReporteNomina')->name('consultaNominas');
    }
);

Route::controller(ManiobristaController::class)->group(
    function () {
        Route::get('/maniobristas', 'index')->name('maniobristas');
        Route::post('/maniobristas/{maniobrista_id}', 'update')->name('maniobristas.update');
    }
);

Route::controller(TurnoController::class)->group(
    function () {
        Route::get('/turnos', 'index')->name('turnos');
        Route::post('/turnos', 'store')->name('turnos.store');
    }
);

Route::controller(LogActivoController::class)->group(
    function ()
    {
        Route::post('/logActivo','store')->name('logActivos.store');
    }
);
