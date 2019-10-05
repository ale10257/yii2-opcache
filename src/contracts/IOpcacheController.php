<?php

namespace ale10257\opcache\contracts;

interface IOpcacheController
{
    /**
     * @return string
     */
    public function actionIndex();

    /**
     * @return string
     */
    public function actionConfig();

    /**
     * @return string
     */
    public function actionFiles();

    /**
     * @return mixed
     */
    public function actionReset();

    /**
     * @return string
     */
    public function actionBlack();

    /**
     * @return mixed
     */
    public function actionInvalidate();
}