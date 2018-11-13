<?php

namespace Sungmee\Editormd;

use Illuminate\Support\ServiceProvider;

class EditormdServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        //配置
        $configPath = __DIR__ . '/../config/editormd.php';
        $this->mergeConfigFrom($configPath, 'editormd');
        $this->publishes([$configPath => config_path('editormd.php')], 'config');

        //路由
        $routePath = __DIR__ . '/Http/routes.php';
        if (! $this->app->routesAreCached()) {
            require $routePath;
        }

        // 静态资源
        $this->publishes([
            __DIR__ . '/../public/vendor/editor.md' => public_path('vendor/'),
        ]);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['editormd'];
    }
}
