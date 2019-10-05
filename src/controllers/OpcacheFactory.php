<?php

namespace ale10257\opcache\controllers;

use ale10257\opcache\contracts\IOpcacheFinder;
use ale10257\opcache\models\OpcacheStatus;
use ale10257\opcache\services\OpcachePresenter;

class OpcacheFactory
{
    /** @var OpcachePresenter */
    private $presenter;
    /** @var \ale10257\opcache\services\OpcacheFinder|\ale10257\opcache\services\OpcacheFinderApi */
    private $finder;

    public function __construct(
        IOpcacheFinder $finder,
        OpcachePresenter $presenter
    ) {
        $this->presenter = $presenter;
        $this->presenter->setUpFormat();
        $this->finder = $finder;
    }

    public function createData($method_name, $arg = null)
    {
        return !$arg ? $this->$method_name() : $this->$method_name($arg);
    }

    private function index()
    {
        $version = $this->finder->getVersion();
        $status = $this->finder->getStatus();
        $presenter = $this->presenter;
        return compact('version', 'status', 'presenter');

    }

    private function config()
    {
        $version = $this->finder->getVersion();
        $directives = $this->finder->getDirectives();
        $directives = $this->presenter->configDirectivesProvider($directives);
        return compact('version', 'directives');
    }

    private function files()
    {
        $version = $this->finder->getVersion();
        $data = $this->finder->getFilesDataProvider($this->presenter);
        $searchModel = $data['searchModel'];
        $provider = $data['provider'];
        return compact('version', 'searchModel', 'provider');
    }

    private function reset()
    {
        return $this->finder->reset();
    }

    private function black()
    {
        $blackList = $this->finder->getBlackList();
        $version = $this->finder->getVersion();
        $blackFile = $this->finder->getDirectives()['opcache.blacklist_filename'];
        return compact('blackList', 'blackFile', 'version');
    }

    private function invalidate($file = null)
    {
        return $this->finder->invalidate($file);
    }

    private function getFinder()
    {
        $status = new OpcacheStatus(opcache_get_status(true));
        $finder = $this->finder;
        return compact('status', 'finder');
    }
}