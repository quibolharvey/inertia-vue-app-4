<?php

use App\Http\Controllers\MySubscriptionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RegisteredAccountController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/subscription', function () {
//     return Inertia::render('Subscription');
// })->name('subscription');

Route::get('/subscription-details', function () {
    return Inertia::render('SubscriptionDetails');
})->name('subscription-details');

Route::get('/my-subscription-details', function () {
    return Inertia::render('MySubscriptionDetails');
})->name('my-subscription-details');

Route::get('/gym-members', function () {
    return Inertia::render('GymMembers');
})->name('gym-members');

Route::get('/registered-account', function () {
    return Inertia::render('RegisteredAccount');
})->name('registered-account');

//new
Route::get('/subscription', fn() => Inertia::render('Subscription'))->name('subscription');

Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');

Route::get('/my-subscription-details', [SubscriptionController::class, 'mySubscriptions'])->name('my-subscription-details');

Route::get('/subscription-details', [SubscriptionController::class, 'allSubscriptions'])->name('subscription-details');

Route::patch('/subscription/{id}/status', [SubscriptionController::class, 'updateStatus'])->name('subscription.updateStatus');

//gym member
Route::get('/gym-members', [SubscriptionController::class, 'getGymMembers'])->name('gym-members');

//reg account
Route::get('/registered-account', [RegisteredAccountController::class, 'index'])->name('registered-account');

//my sub
Route::middleware(['auth'])->get('/my-subscription', [MySubscriptionController::class, 'index'])->name('my-subscription');




require __DIR__.'/auth.php';
