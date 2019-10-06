<?php

use ale10257\opcache\utils\Translator;
use yii\grid\GridView;

/**
 * @var \yii\web\View $this
 * @var ale10257\opcache\controllers\DefaultController $context
 * @var string $version
 * @var ale10257\opcache\models\FileFilterModel $searchModel
 * @var \yii\data\ArrayDataProvider $provider
 **/
$this->title = $version;
$js = "
    $(function () {
        $('.invalidate').click(function () {
            $.post($(this).data('url'), {file: $(this).data('file')});
        });
    });
";
$this->registerJs($js, \yii\web\View::POS_END);
?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4><?= \ale10257\opcache\utils\Helper::getTitlePage() ?></h4>
        <div class="panel-title"><?= $version ?></div>
    </div>
    <div class="panel-body">
        <?= $this->render('_menu') ?>
        <?= GridView::widget(
            [
                'filterModel' => $searchModel,
                'dataProvider' => $provider,
                'layout' => "<span class='pull-right'>{summary}</span>{pager}\n{items}\n{pager}",
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function ($model) {
                            $opcache_host = Yii::$app->request->get('opcache_host');
                            $btn = \yii\helpers\Html::button(Translator::t('invalidate'), [
                                'data' => [
                                    'url' => \yii\helpers\Url::to(['invalidate', 'opcache_host' => $opcache_host]),
                                    'file' => $model['full_path']
                                ],
                                'class' => ['class' => 'btn btn-default btn-sm invalidate']
                            ]);
                            return $btn;
                        }
                    ],
                    'full_path:raw:' . Translator::t('full_path'),
                    'hits:raw:' . Translator::t('hits'),
                    'memory_consumption:shortSize:' . Translator::t('memory_consumption'),
                    [
                        'attribute' => 'timestamp',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model['timestamp'], 'short');
                        },
                        'label' => Translator::t('file_timestamp'),
                    ],
                    [
                        'attribute' => 'last_used_timestamp',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model['last_used_timestamp'], 'short');
                        },
                        'label' => Translator::t('last_used_timestamp'),
                    ],

                ],
            ]
        ); ?>
    </div>
</div>
