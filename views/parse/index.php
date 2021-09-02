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
                    return '';
                }
            ]
        ]
    ]
);
?>
