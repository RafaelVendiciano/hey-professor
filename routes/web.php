<?php

use App\Http\Controllers\{ProfileController, QuestionController, DashboardController, Question, Auth\Github};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (app()->isLocal()) {
        auth()->loginUsingId(1);
        return to_route('dashboard');
    }
    return view('welcome');
});

Route::get('/github/login', Github\RedirectController::class)->name('github.login');
Route::get('/github/callback', Github\CallbackController::class)->name('github.callback');

Route::middleware(['auth'])->group(function () {
    // Removed verified
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    // --
    // Question Routes
    Route::get('/question/index', [QuestionController::class, 'index'])->name('question.index');
    Route::post('/question/store', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/question/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    Route::put('/question/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::patch('/question/{question}/archive', [QuestionController::class, 'archive'])->name('question.archive');
    Route::patch('/question/{id}/restore', [QuestionController::class, 'restore'])->name('question.restore');
    Route::delete('/question/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
    Route::post('/question/like/{question}', Question\LikeController::class)->name('question.like');
    Route::post('/question/dislike/{question}', Question\DislikeController::class)->name('question.dislike');
    Route::put('/question/publish/{question}', Question\PublishController::class)->name('question.publish');

    // --
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
