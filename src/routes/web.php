<?php declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OAuth\CallbackFromProviderController;
use App\Http\Controllers\OAuth\RedirectToProviderController;
use Illuminate\Support\Facades\Route;

use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\ProjectDetail\ProjectDetail;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Setting\Setting;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/{provider}/redirect', RedirectToProviderController::class)->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', CallbackFromProviderController::class)->name('oauth.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', Projects::class)->name('projects');
    Route::get('/projects/new', NewProject::class)->name('project.new');
    Route::get('/projects/{projectId}', ProjectDetail::class)->name('project.detail');

    Route::get('/settings', Setting::class)->name('settings');
});

require __DIR__.'/auth.php';
