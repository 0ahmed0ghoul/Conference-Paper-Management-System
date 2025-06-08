<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaperController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Reviewer\DashboardController as ReviewerDashboardController;
use App\Http\Controllers\Chair\DashboardController as ChairDashboardController;
// Public Routes
Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/speakers', [PageController::class, 'speakers'])->name('speakers');
Route::get('/schedule', [PageController::class, 'schedule'])->name('schedule');
Route::get('/accepted-papers', [PaperController::class, 'acceptedPapers'])->name('accepted-papers');
Route::get('/papers/download/{paper}', [PaperController::class, 'download'])->name('papers.download');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('signup');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/waiting', function () {
        $user = Auth::user();
        if ($user->role === 'author' || $user->status === 'approved') {
            return match ($user->role) {
                'author' => redirect()->route('author.dashboard'),
                'reviewer' => redirect()->route('reviewer.dashboard'),
                'chair' => redirect()->route('chair.dashboard'),
                default => view('auth.waiting', compact('user')),
            };
        }
        return view('auth.waiting', compact('user'));
    })->name('waiting.page');

    // Author Routes
    Route::prefix('author')->middleware(['role:author', 'checkStatus'])->group(function () {
        Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('author.dashboard');
        Route::post('/submit-paper', [AuthorDashboardController::class, 'submitPaper'])->name('submitPaper');
        Route::match(['post', 'delete'], '/author/papers/{paper}/remove', [AuthorDashboardController::class, 'removePaper'])->name('removePaper');

    });

    // Chair Routes (handles both chair and super chair logic internally)
    Route::prefix('chair')->middleware(['role:chair', 'checkStatus'])->group(function () {
        Route::match(['get', 'post'], '/dashboard', [ChairDashboardController::class, 'index'])->name('chair.index');
        Route::post('/approve/{userId}', [ChairDashboardController::class, 'approve'])->name('chair.approve');
        Route::post('/chair/assign', [ChairDashboardController::class, 'assign'])->name('chair.assign');
    });


        // routes/web.php
        Route::prefix('reviewer')->middleware(['auth', 'role:reviewer'])->group(function () {
            Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('reviewer.dashboard');
            Route::post('/reviews/{assignment}', [ReviewerDashboardController::class, 'submitReview'])->name('reviewer.submitReview');
            Route::post('/assignments/{assignment}/accept', [ReviewerDashboardController::class, 'accept'])->name('reviewer.accept');
            Route::post('/assignments/{assignment}/reject', [ReviewerDashboardController::class, 'reject'])->name('reviewer.reject');
        });

});
