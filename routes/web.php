<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationsResponseController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Evaluation's routes
Route::post('/ajouter-evaluation', [EvaluationController::class, 'add'])->name('evaluation.add');
Route::post('/modifier-evaluation', [EvaluationController::class, 'updateEval'])->name('evaluation.update');
Route::post('/supprimer-evaluation', [EvaluationController::class, 'deleteEval'])->name('evaluation.deleteEval');
Route::post('/ajouter-etudiants', [EvaluationController::class, 'addStudents'])->name('evaluation.addStudents');
// ---------------------- //

// EvaluationsResponse's routes
Route::post('/envoyer-evaluation', [EvaluationsResponseController::class, 'add'])->name('evaluation_response.add');
Route::post('/correction-automatique-evaluations', [EvaluationsResponseController::class, 'autoCorrection'])->name('evaluation_response.autoCorrection');
// ---------------------- //