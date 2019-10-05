<?php

namespace ale10257\opcache\services;

use ale10257\opcache\contracts\IOpcacheFinder;
use ale10257\opcache\models\OpcacheStatus;
use ale10257\opcache\utils\Helper;

/**
 * Class OpcacheFinder
 * @package insolita\opcache\services
 */
class OpcacheFinder implements IOpcacheFinder, \JsonSerializable
{
    /** @var array */
    private $directives = [];
    /** @var string */
    private $version = 'Unknown';
    /**  @var array */
    private $blackList = [];
    /** @var array */
    private $files = [];
    /** @var \ale10257\opcache\models\OpcacheStatus */
    private $status;

    /**
     * OpcacheFinder constructor.
     */
    public function __construct()
    {
        try {
            $config = opcache_get_configuration();
            if ($config['directives']['opcache.enable'] != 1) {
                throw new \DomainException('Opcache Extension Disabled!');
            }
            $this->directives = Helper::remove($config, 'directives', []);
            $this->blackList = Helper::remove($config, 'blacklist', []);
            $this->version = Helper::getValue($config, 'version.opcache_product_name', '')
                . Helper::getValue($config, 'version.version', 'Unknown');
            $this->status = new OpcacheStatus(opcache_get_status(true));
            unset($config, $status);
        } catch (\Throwable $e) {
            throw new \DomainException($e->getMessage());
        }
    }

    public function getDirectives()
    {
        return $this->directives;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getBlackList()
    {
        return $this->blackList ? $this->blackList : [];
    }

    public function getFiles()
    {
        return $this->files ? array_values($this->files) : [];
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'directives' => $this->directives,
            'version' => $this->version,
            'blackList' => $this->blackList,
            'files' => $this->files,
        ];
    }


    public function getFilesDataProvider(): array
    {
        $status = opcache_get_status(true);
        $this->files = Helper::remove($status, 'scripts', []);
        $searchModel = (new OpcachePresenter())->createFileFilterModel($this->files);
        $provider = $searchModel->search(\Yii::$app->request->getQueryParams());
        return compact('searchModel', 'provider');
    }

    public function invalidate($file = null)
    {
        return ['result' => opcache_invalidate($file, true)];
    }

    public function reset()
    {
        return ['result' => opcache_reset()];
    }
}
