<?php declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OAuth\CallbackFromProviderController;
use App\Http\Controllers\OAuth\RedirectToProviderController;
use Illuminate\Support\Facades\Route;

use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\ProjectDetail\ProjectDetail;
use App\Livewire\Development\Development;
use App\Livewire\InDevelopments\InDevelopments;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Setting\Setting;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/{provider}/redirect', RedirectToProviderController::class)->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', CallbackFromProviderController::class)->name('oauth.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('projects')->group(function () {
        Route::get('/', Projects::class)->name('projects');
        Route::get('/new', NewProject::class)->name('project.new');
        Route::get('/{projectId}', ProjectDetail::class)->name('project.detail');
    });

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/inDevelopments', InDevelopments::class)->name('inDevelopments');

    Route::get('/settings', Setting::class)->name('settings');
    
    Route::prefix('developments')->group(function () {
        Route::get('/{projectId}', Development::class)->name('development');
    });
});

require __DIR__.'/auth.php';