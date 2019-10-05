<?php

namespace ale10257\opcache;

use ale10257\opcache\contracts\IOpcacheFinder;
use ale10257\opcache\services\OpcacheFinder;
use ale10257\opcache\services\OpcacheFinderApi;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $host = $app->request->get('opcache_host');
        $opcache_module = $app->getModule('opcache');
        $container->set(IOpcacheFinder::class, function () use ($host, $opcache_module) {
            if (!$opcache_module->local) {
                return new OpcacheFinder();
            } else {
                return $host && $opcache_module->local ? new OpcacheFinderApi() : new OpcacheFinder();
            }
        });
    }
}