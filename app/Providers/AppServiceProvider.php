<?php

namespace App\Providers;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Garante que o config seja registrado antes de qualquer outra coisa
        $this->app->singleton('config', function (Application $app) {
            $config = new Repository();

            // Carrega configurações básicas se não estiverem cacheadas
            if (!$app->configurationIsCached()) {
                $loader = $app->get('config.loader');
                $config->set(require $app->configPath('app.php'));
            }

            return $config;
        });

        // Adia o carregamento de outros providers se houver erro
        $this->app->bind('config.loader', function ($app) {
            return new class {
                public function load($group) {
                    return [];
                }
            };
        });
    }

    public function boot()
    {
        // Garante que o sistema de configuração está pronto
        if ($this->app->configurationIsCached()) {
            $this->app->booted(function () {
                $this->app['config']->set(require $this->app->getCachedConfigPath());
            });
        }
    }
}
