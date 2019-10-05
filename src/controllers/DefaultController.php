<?php

namespace ale10257\opcache\controllers;

use ale10257\opcache\contracts\IOpcacheController;
use ale10257\opcache\utils\Translator;
use yii\helpers\Html;
use yii\web\Controller;

/**
 * Class DefaultController
 *
 * @package backend\modules\opcache\controllers
 */
class DefaultController extends Controller implements IOpcacheController
{
    /** @var OpcacheFactory */
    private $factory;

    public function __construct($id, $module, OpcacheFactory $factory)
    {
        parent::__construct($id, $module);
        $this->factory = $factory;
    }

    public function actionIndex()
    {
        return $this->render('index', $this->factory->createData('index'));
    }

    /**
     * @return string
     */
    public function actionConfig()
    {
        return $this->render('config', $this->factory->createData('config'));
    }

    public function actionFiles()
    {
        return $this->render('files', $this->factory->createData('files'));
    }

    public function actionReset()
    {
        $reset = $this->factory->createData('reset');
        if ($reset['result']) {
            \Yii::$app->session->setFlash('success', Translator::t('cache_reset_success'));
        } else {
            \Yii::$app->session->setFlash('error', Translator::t('cache_reset_fail'));
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionBlack()
    {
        return $this->render('blacklist', $this->factory->createData('black'));
    }

    public function actionInvalidate()
    {
        $file = \Yii::$app->request->post('file');
        $result = $this->factory->createData('invalidate', $file);
        if ($result['result']) {
            \Yii::$app->session->setFlash(
                'success',
                Translator::t('file_invalidate_success') . ' - ' . Html::encode($file)
            );
        } else {
            \Yii::$app->session->setFlash(
                'error',
                Translator::t('file_invalidate_fail') . ' - ' . Html::encode($file)
            );
        }
        $this->redirect(\Yii::$app->request->referrer);
    }
}
