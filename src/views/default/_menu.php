<?php

/* @var $this \yii\web\View */

use ale10257\opcache\utils\Translator;

echo \yii\bootstrap\Nav::widget([
    'id' => 'opcache_nav_menu',
    'options' => ['class' => 'nav nav-tabs'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Translator::t('status'),
            'url' => ['index', 'opcache_host' => Yii::$app->request->get('opcache_host')]
        ],
        [
            'label' => Translator::t('config'),
            'url' => ['config', 'opcache_host' => Yii::$app->request->get('opcache_host')]
        ],
        [
            'label' => Translator::t('file_list'),
            'url' => ['files', 'opcache_host' => Yii::$app->request->get('opcache_host')]
        ],
        [
            'label' => Translator::t('black_list'),
            'url' => ['black', 'opcache_host' => Yii::$app->request->get('opcache_host')]
        ],
        [
            'label' => Translator::t('reset'),
            'url' => ['reset', 'opcache_host' => Yii::$app->request->get('opcache_host')]
        ],
    ]
]);