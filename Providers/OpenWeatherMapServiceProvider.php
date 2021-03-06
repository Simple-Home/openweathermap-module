<?php

namespace Modules\OpenWeatherMap\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\SettingManager;

class OpenWeatherMapServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /** Settings this integration needs to create  */
    public function createSettings()
    {
        SettingManager::register('apiKey', '', 'string', 'openweathermap');
        SettingManager::register('location', '', 'string', 'openweathermap');
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->createSettings();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/OpenWeatherMap');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'OpenWeatherMap');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'OpenWeatherMap');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}