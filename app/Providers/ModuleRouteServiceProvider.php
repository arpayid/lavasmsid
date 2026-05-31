<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadModuleRoutes();
    }

    protected function loadModuleRoutes(): void
    {
        $modulesPath = app_path('Modules');

        if (! is_dir($modulesPath)) {
            return;
        }

        foreach (scandir($modulesPath) as $module) {
            $routesFile = "{$modulesPath}/{$module}/routes.php";

            if (is_file($routesFile)) {
                Route::middleware('web')
                    ->group($routesFile);
            }
        }
    }
}
