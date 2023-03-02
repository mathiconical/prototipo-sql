<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;

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
    return redirect('login');
});

Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions/create', [QuestionController::class, 'create'])->name('questions.create')->middleware(['IsAdmin']);
    Route::get('/questions/{answer}', [QuestionController::class, 'show'])->name('questions.show');
    Route::patch('/questions/{answer}', [QuestionController::class, 'update'])->name('questions.update')->middleware(['IsAdmin']);
    Route::delete('/questions/{answer}', [QuestionController::class, 'destroy'])->name('questions.destroy')->middleware(['IsAdmin']);
    Route::post('/questionReset', [QuestionController::class, 'reset'])->name('questions.reset')->middleware(['IsAdmin']);
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store')->middleware(['IsAdmin']);
    Route::post('/runQuery', [QuestionController::class, 'runQuery'])->name('questions.runQuery');
    Route::post('/saveQuery', [QuestionController::class, 'saveQuery'])->name('questions.saveQuery');
    Route::post('/feedback', [UserController::class, 'saveFeedback'])->name('users.saveFeedback');
    Route::post('/getNota', [UserController::class, 'getNota'])->name('users.getNota');

    Route::get('/dashboard', [QuestionController::class, 'dashboard'])->name('dashboard');
});

require __DIR__ . '/auth.php';
