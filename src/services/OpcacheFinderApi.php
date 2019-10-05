<?php

namespace ale10257\opcache\services;

use ale10257\opcache\contracts\IOpcacheFinder;
use ale10257\opcache\models\OpcacheStatus;
use yii\httpclient\Client;
use Yii;
use yii\httpclient\Response;

class OpcacheFinderApi implements IOpcacheFinder
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
    /** @var Client */
    private $client;
    /** @var string */
    private $base_url;

    public function __construct()
    {
        $this->client = (new Client())
            ->createRequest()
            ->setHeaders(['Authorization' => 'Bearer ' . Yii::$app->params['opcache_host']['token']]);
        $this->base_url = Yii::$app->params['opcache_host']['domain'] . '/opcache/api/';
        $response = (clone $this->client)
            ->setUrl($this->base_url . 'get-finder')
            ->send();
        if (!$response->isOk) {
            throw new \DomainException($response->content);
        }
        foreach ($response->data['finder'] as $key => $item) {
            $this->$key = $item;
        }
        $this->status = (new OpcacheStatus())->setData($response->data['status']);
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

    public function getFilesDataProvider()
    {
        $url = $this->base_url . 'files';
        if (Yii::$app->request->queryString) {
            $url .= '?' . Yii::$app->request->queryString;
        }
        $response = (clone $this->client)
            ->setUrl($url)
            ->send();
        if (!$response->isOk) {
            throw new \DomainException($response->content);
        }
        $searchModel = (new OpcachePresenter())->createFileFilterModel($this->files);
        $searchModel->full_path = $response->data['searchModel']['full_path'];
        $provider = $searchModel->setProvider();
        $provider->setModels($response->data['provider']['models']);
        $provider->setKeys($response->data['provider']['keys']);
        $provider->setTotalCount($response->data['provider']['total_count']);
        return compact('searchModel', 'provider');
    }

    public function invalidate($file = null)
    {
        $url = $this->base_url . 'invalidate';
        /** @var Response $response */
        $response = (clone $this->client)
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setData(['file' => Yii::$app->request->post('file')])
            ->send();
        if (!$response->isOk) {
            throw new \DomainException($response->content);
        }
        return $response->data;
    }

    public function reset()
    {
        $url = $this->base_url . 'reset';
        $response = (clone $this->client)
            ->setUrl($url)
            ->send();
        if (!$response->isOk) {
            throw new \DomainException($response->content);
        }
        return $response->data;
    }
}