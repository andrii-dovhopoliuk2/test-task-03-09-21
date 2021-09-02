<?php

use yii\grid\GridView;
use yii\helpers\Url;

/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $out = '<a href="' . Url::toRoute(['parse/download', 'id' => $model->id]) . '">' . Yii::t('app', 'download') . '</a>';
                    $out .= ' | ';
                    $out .= '<a href="' . Url::toRoute(['parse/delete', 'id' => $model->id]) . '">' . Yii::t('app', 'delete') . '</a>';
                    return $out;
                }
            ]
        ]
    ]
);
?>
