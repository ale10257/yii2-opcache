<?php

namespace ale10257\opcache\utils;


class Helper
{
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }


    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        return $default;
    }

    /**
     * Converts a word like "send_email" to "sendEmail".
     * @param string $word to lowerCamelCase
     * @return string
     */
    public static function variablize($word)
    {
        $word = str_replace(
            ' ',
            '',
            ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word))
        );

        return strtolower($word[0]) . substr($word, 1);
    }

    public static function getTitlePage()
    {
        $title = 'Host: ';
        return empty(\Yii::$app->params['opcache_host']) ? $title . 'local' : $title . \Yii::$app->params['opcache_host']['domain'];
    }
}
