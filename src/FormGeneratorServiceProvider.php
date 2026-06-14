<?php

namespace Ganesh\FormGenerator;

use Illuminate\Support\ServiceProvider;

class FormGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'form-generator');

        $this->mergeConfigFrom(__DIR__ . '/config/form-generator.php', 'form-generator');

        // Publish Config (Optional)
        $this->publishes([
            __DIR__ . '/config/form-generator.php' => config_path('form-generator.php'),
        ], 'config');

        // Publish Views (Optional)
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/form-generator'),
        ], 'views');

        // Publish Migrations (Optional)
        if (!class_exists('CreateFormsTable')) {
            $this->publishes([
                __DIR__ . '/database/migrations/2025_02_05_000000_create_forms_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_forms_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {
        // Merge Config
        // $this->mergeConfigFrom(__DIR__ . '/config/form-generator.php', 'form-generator');
    }
}
