<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteMemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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

});
