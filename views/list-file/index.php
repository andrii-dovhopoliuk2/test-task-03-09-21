<?php

use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'List files');

/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<a href="<?= Url::toRoute(['parse/parse-all']) ?>">Parse all files</a>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $out = '<a href="' . Url::toRoute(['parse/parse', 'path' => $model['path']]) . '">' . Yii::t('app', 'parse') . '</a>';
                    $out .= ' | ';
                    $out .= '<a href="' . Url::toRoute(['list-file/delete', 'path' => $model['path']]) . '">' . Yii::t('app', 'delete') . '</a>';
                    return $out;
                }
            ]
        ]
    ]
);
?>

