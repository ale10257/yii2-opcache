<?php

use ale10257\opcache\utils\Translator;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var ale10257\opcache\controllers\DefaultController $context
 * @var string $version
 * @var \yii\data\ArrayDataProvider $directives
 **/
$this->title = $version;

?>

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="panel-title"><?= $version ?></div>
    </div>
    <div class="panel-body">
        <?= $this->render('_menu') ?>
        <?= GridView::widget(
            [
                'summary' => '',
                'dataProvider' => $directives,
                'layout' => '{items}',
                'columns' => [
                    [
                        'attribute' => 'option',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::tag('b', $model['option']);
                        },
                        'header' => Translator::t('option'),
                    ],
                    [
                        'attribute' => 'value',
                        'format' => 'raw',
                        'header' => Translator::t('value'),
                    ],
                    [
                        'attribute' => 'hint',
                        'format' => 'raw',
                        'header' => Translator::t('hint'),
                    ],
                ],
            ]
        ) ?>
    </div>
</div>
