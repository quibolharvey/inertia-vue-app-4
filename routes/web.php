<?php

use App\Http\Controllers\MySubscriptionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RegisteredAccountController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

//kinabagohan
Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

// Handle verify email link
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Show the verify email page
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

// Handle the actual email verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend the verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


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

Route::put('/registered-account/{user}/toggle-status', [RegisteredAccountController::class, 'toggleStatus'])->name('registered-account.toggle-status');


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

//trash
Route::get('/trash', function () {
    return Inertia::render('Trash');
})->name('trash');




require __DIR__.'/auth.php';
