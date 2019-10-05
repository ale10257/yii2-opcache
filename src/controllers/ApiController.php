<?php

namespace ale10257\opcache\controllers;

use ale10257\opcache\contracts\IOpcacheController;
use yii\data\ArrayDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ApiController extends Controller implements IOpcacheController
{
    /** @var OpcacheFactory */
    private $factory;

    public function __construct($id, $module, OpcacheFactory $factory)
    {
        parent::__construct($id, $module);
        $this->factory = $factory;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actionGetFinder()
    {
        return $this->factory->createData('getFinder');
    }

    public function actionIndex()
    {
        return $this->factory->createData('index');
    }

    public function actionConfig()
    {
        return $this->factory->createData('config');
    }

    public function actionFiles()
    {
        $data = $this->factory->createData('files');
        /** @var ArrayDataProvider $provider_obj */
        $provider_obj = $data['provider'];
        $provider = [
            'total_count' => $provider_obj->getTotalCount(),
            'keys' => array_keys($provider_obj->getKeys()),
            'models' => $provider_obj->getModels(),
        ];
        $data['provider'] = $provider;
        return $data;
    }

    public function actionReset()
    {
        return $this->factory->createData('reset');
    }

    public function actionBlack()
    {
        return $this->factory->createData('black');
    }

    public function actionInvalidate()
    {
        $data = json_decode(\Yii::$app->request->rawBody, true);
        return $this->factory->createData('invalidate', $data['file']);
    }
}