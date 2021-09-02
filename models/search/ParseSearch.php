<?php


namespace app\models\search;


use app\models\Parse;
use yii\data\ActiveDataProvider;

class ParseSearch extends Parse
{
    public function search($params, $format)
    {
        $dataProvider = Parse::find()->where(['format' => $format]);
        $queryProvider = new ActiveDataProvider([
            'query' => $dataProvider,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $queryProvider;
        }

        return $queryProvider;
    }
}