<?php

namespace ale10257\opcache\models;

use ale10257\opcache\utils\Helper;

/**
 * Class OpcacheStatus
 *
 * @package insolita\opcache\models
 */
class OpcacheStatus implements \JsonSerializable
{
    /** @var bool */
    private $opcacheEnabled = false;
    /** @var bool */
    private $cacheFull = false;
    /** @var bool */
    private $restartPending = false;
    /** @var bool */
    private $restartInProgress = false;
    /** @var array */
    private $memoryUsage = [];
    /** @var array */
    private $statistics = [];
    /**@var array */
    private $stringsInfo = [];

    /**
     * OpcacheStatus constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if ($config) {
            $this->memoryUsage = Helper::remove($config, 'memory_usage');
            $this->statistics = Helper::remove($config, 'opcache_statistics');
            $this->stringsInfo = Helper::remove($config, 'interned_strings_usage');
            foreach ($config as $name => $value) {
                $name = Helper::variablize($name);
                if (isset($this->{$name})) {
                    $this->{$name} = $value;
                }
            }
        }
    }

    public function setData(array $config)
    {
        foreach ($config as $key => $item) {
            $this->$key = $item;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function getOpcacheEnabled()
    {
        return $this->opcacheEnabled;
    }

    /**
     * @return bool
     */
    public function getCacheFull()
    {
        return $this->cacheFull;
    }

    /**
     * @return bool
     */
    public function getRestartPending()
    {
        return $this->restartPending;
    }

    /**
     * @return bool
     */
    public function getRestartInProgress()
    {
        return $this->restartInProgress;
    }

    /**
     * @return array
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @return array
     */
    public function getStringsInfo()
    {
        return $this->stringsInfo;
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
            'opcacheEnabled' => $this->opcacheEnabled,
            'cacheFull' => $this->cacheFull,
            'restartPending' => $this->restartPending,
            'restartInProgress' => $this->restartInProgress,
            'memoryUsage' => $this->memoryUsage,
            'statistics' => $this->statistics,
            'stringsInfo' => $this->stringsInfo,
        ];
    }
}
