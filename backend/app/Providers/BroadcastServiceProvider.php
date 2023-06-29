<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//Broadcast::routes();
 Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);
//Broadcast::routes(['middleware' => ['auth:api'], 'prefix' => 'api']);
//Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);
 
        require base_path('routes/channels.php');
    }
}
