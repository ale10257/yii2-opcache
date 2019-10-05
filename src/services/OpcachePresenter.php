<?php

namespace ale10257\opcache\services;

use ale10257\opcache\models\FileFilterModel;
use ale10257\opcache\utils\Translator;
use yii\data\ArrayDataProvider;


class OpcachePresenter
{
    public function setUpFormat()
    {
        \Yii::$app->formatter->booleanFormat = [
            '<i class="glyphicon glyphicon-unchecked"></i>',
            '<i class="glyphicon glyphicon-check"></i>',
        ];
        \Yii::$app->formatter->sizeFormatBase = 1024;
    }

    /**
     * @param array $directives
     * @return \yii\data\ArrayDataProvider
     */
    public function configDirectivesProvider(array $directives)
    {
        $data = [];
        foreach ($directives as $key => $value) {
            $data[]
                = [
                'option' => $key,
                'hint' => $this->translateHint($key),
                'value' => $this->formatValue($value, $key),
            ];
        }
        return new ArrayDataProvider(
            [
                'allModels' => $data,
                'key' => 'option',
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'attributes' => ['option' => []],
                    'defaultOrder' => ['option' => SORT_ASC],
                ],
            ]
        );
    }

    /**
     * @param array|null $files
     * @return FileFilterModel|\yii
     */
    public function createFileFilterModel(array $files = null)
    {
        return new FileFilterModel($files);
    }

    /**
     * @param $value
     * @param $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function formatStatistic($value, $key)
    {
        if (in_array($key, ['start_time', 'last_restart_time'])) {
            $value = $value ? \Yii::$app->formatter->asDatetime($value) : ' - ';
        } elseif (in_array($key, ['blacklist_miss_ratio', 'opcache_hit_rate'])) {
            $value = round($value, 2) . '%';
        }
        return $value;
    }

    /**
     * @param $value
     * @param $key
     *
     * @return string
     */
    public function formatMemory($value, $key)
    {
        if (in_array($key, ['used_memory', 'buffer_size', 'free_memory', 'wasted_memory'])) {
            $value = \Yii::$app->formatter->asShortSize($value);
        } elseif ($key == 'current_wasted_percentage') {
            $value = round($value, 3) . '%';
        }
        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function formatBool($value)
    {
        return \Yii::$app->formatter->asBoolean($value);
    }

    /**
     * @param $message
     * @return string
     */
    protected function translateHint($message)
    {
        return Translator::hint($message);
    }

    /**
     * @param $value
     * @param $key
     * @return string
     */
    protected function formatValue($value, $key = null)
    {

        if (in_array($key, ['opcache.memory_consumption'])) {
            return \Yii::$app->formatter->asShortSize($value);
        }

        $type = gettype($value);
        switch ($type) {
            case 'boolean':
                return \Yii::$app->formatter->asBoolean($value);
            case 'float':
                return \Yii::$app->formatter->asDecimal($value);
            default:
                return $value;
        }
    }
}
