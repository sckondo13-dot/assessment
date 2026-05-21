<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteMemberController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationResultController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::resource('sites', SiteController::class);
});

Route::middleware('auth')->group(function () {

    Route::resource('sites', SiteController::class);

    Route::get(
        '/sites/{site}/members/create',
        [SiteMemberController::class, 'create']
    )->name('site-members.create');

    Route::post(
        '/sites/{site}/members',
        [SiteMemberController::class, 'store']
    )->name('site-members.store');

    Route::delete(
        '/site-members/{siteMember}',
        [SiteMemberController::class, 'destroy']
    )->name('site-members.destroy');

    Route::resource(
        'sites.questions',
        QuestionController::class
    );

    Route::get(
        '/evaluations',
        [EvaluationController::class, 'index']
    )->name('evaluations.index');

    Route::post(
        '/evaluations/confirm',
        [EvaluationController::class, 'confirm']
    )->name('evaluations.confirm');

    Route::post(
        '/evaluations/store',
        [EvaluationController::class, 'store']
    )->name('evaluations.store');

    Route::middleware('auth')->group(function () {

        Route::get(
            '/evaluation-results',
            [EvaluationResultController::class, 'index']
        )->name('evaluation-results.index');

    });
});
