<?php

namespace ale10257\opcache\models;

use ale10257\opcache\utils\Translator;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * Class FileFilterModel
 * @package insolita\opcache\models
 */
class FileFilterModel extends Model
{
    /** @var string */
    public $full_path;
    /**  @var array */
    private $files;

    /**
     * FileFilterModel constructor.
     * @param array $files
     */
    public function __construct(array $files = null)
    {
        $this->files = $files;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['full_path'], 'trim'],
            [['full_path'], 'string', 'max' => 100],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'full_path' => Translator::t('full_path'),
        ];
    }

    /**
     * @param $params
     * @return \yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        $provider = $this->setProvider($this->files);
        if ($this->load($params) && $this->validate() && !empty($this->full_path)) {
            $provider->allModels = array_filter($this->files, [$this, 'pathFilter']);
        }
        return $provider;
    }

    /**
     * @param $item
     * @return bool
     */
    protected function pathFilter($item)
    {
        if (mb_strpos($item['full_path'], $this->full_path) !== false) {
            return true;
        }
        return false;
    }

    public function setProvider($all_models = [])
    {
        return new ArrayDataProvider(
            [
                'allModels' => $all_models,
                'key' => 'full_path',
                'pagination' => ['pageSize' => 20],
                'sort' => [
                    'attributes' => [
                        'full_path' => [],
                        'timestamp' => [],
                        'hits' => [],
                        'memory_consumption' => [],
                        'last_used_timestamp' => [],
                    ],
                    'defaultOrder' => ['full_path' => SORT_ASC],
                ],
            ]
        );
    }
}
