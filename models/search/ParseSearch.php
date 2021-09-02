<?php


namespace app\models\search;


use app\models\Parse;
use yii\data\ActiveDataProvider;

class ParseSearch extends Parse
{
    public function search($params)
    {
        $dataProvider = Parse::find();
        $queryProvider = new ActiveDataProvider([
            'query' => $dataProvider,
            'sort' => false
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $queryProvider;
        }

        return $queryProvider;
    }
}