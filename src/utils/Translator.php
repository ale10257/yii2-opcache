<?php

namespace ale10257\opcache\utils;


class Translator
{
    public static function hint($message, $params = [], $language = null)
    {
        self::setLanguage();
        return \Yii::t('opcache/hint', $message, $params, $language);
    }

    public static function status($message, $params = [], $language = null)
    {
        self::setLanguage();
        return \Yii::t('opcache/status', $message, $params, $language);
    }

    public static function t($message, $params = [], $language = null)
    {
        self::setLanguage();
        return \Yii::t('opcache/interface', $message, $params, $language);
    }

    private static function setLanguage()
    {
        if (\Yii::$app->language !== 'en' && \Yii::$app->language !== 'ru') {
            /** @var \ale10257\opcache\OpcacheModule $opcache_module */
            $opcache_module = \Yii::$app->getModule('opcache');
            \Yii::$app->language = $opcache_module->language;
        }
    }
}
