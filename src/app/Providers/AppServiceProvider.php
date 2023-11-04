<?php declare(strict_types=1);

namespace App\Providers;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

use App\Livewire\Utils\Message\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(
            // It will only be enabled outside of production, though.
            ! app()->isProduction()
        );
        
        Component::macro('notify', function (Message $message) {

            $this->dispatch('notify', message: $message->toArray());
        });
    }
}
