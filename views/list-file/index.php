<?php

use yii\grid\GridView;
use yii\helpers\Url;

/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<a href="">Parse all files</a>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $out = '<a href="' . Url::toRoute(['parser/parse', 'path' => $model['path']]) . '">' . Yii::t('app', 'parse') . '</a>';
                    $out .= ' | ';
                    $out .= '<a href="' . Url::toRoute(['list-file/delete', 'path' => $model['path']]) . '">' . Yii::t('app', 'delete') . '</a>';
                    return $out;
                }
            ]
        ]
    ]
);
?>

