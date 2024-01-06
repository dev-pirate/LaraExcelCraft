<?php

namespace DevPirate\LaraExcelCraft\Providers;
use DevPirate\LaraExcelCraft\Console\Commands\RemoveExpiredFilesCommand;
use DevPirate\LaraExcelCraft\Console\Kernel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class LaraExcelCraftProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RemoveExpiredFilesCommand::class,
            ]);
        }

        //$this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'lara_excel_craft');
        Blade::componentNamespace('DevPirate\\LaraExcelCraft\\View\\Components', 'lara-excel-craft');

        $folderPath = public_path('vendor/lara-excel-craft');

        if (file_exists($folderPath)) {
            // Delete the folder and its contents
            File::deleteDirectory($folderPath);
        }

        $this->publishes([
            __DIR__ . '/../../public' => $folderPath,
        ], 'lara-excel-craft-assets');

        $this->publishes([
            __DIR__.'/../../config/lara-excel-craft.php' => config_path('lara-excel-craft.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->singleton('lara_excel_craft.console.kernel', function ($app, $events) {
            return new Kernel($app, $events);
        });
    }
}
