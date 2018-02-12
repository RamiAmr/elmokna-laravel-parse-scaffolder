<?php

namespace approcks\laravelParseScaffolder;

use Illuminate\Support\ServiceProvider;

class ParseScaffoldingServiceProvider extends ServiceProvider
{

    protected static $commands = [
        'approcks\\laravelParseScaffolder\\Console\\Commands\\Scaffold',
    ];

    /**
     * The boot() method is used to boot any routes, event listeners, or any other functionality you want to add to your package
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * The register() method is used to bind any classes or functionality into the app container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(static::$commands);
    }
}
