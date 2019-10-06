<?php

namespace ale10257\opcache;

use yii\base\Module;
use Yii;

/**
 * Class OpcacheModule
 *
 * @package insolita\opcache
 */
class OpcacheModule extends Module
{
    public $config_hosts_file;
    /** @var array
     * [
     *    'host_alias' => ['domain' => 'example.com', 'token' => 'token']
     *    'host_alias1' => ['domain' => 'example1.com', 'token' => 'token1']
     * ]
     */
    public $hosts = [];

    public $local = true;

    public function init()
    {
        parent::init();
        if (!$this->config_hosts_file) {
            $file = Yii::getAlias('@app/config/opcache_hosts.php');
            if (is_file($file)) {
                $this->hosts = require $file;
            }
        }
        if ($this->local && $host = Yii::$app->request->get('opcache_host')) {
            Yii::$app->params['opcache_host'] = $this->hosts[$host];
        }
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['opcache/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@ale10257/opcache/messages',
            'fileMap' => [
                'opcache/interface' => 'interface.php',
                'opcache/hint' => 'hint.php',
                'opcache/status' => 'status.php',
            ],
        ];
    }
}
