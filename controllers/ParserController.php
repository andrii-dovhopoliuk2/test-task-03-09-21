<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ParserController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionParse($path)
    {
        if (file_exists($path)) {
            echo $path;
        }
        Yii::$app->db->createCommand(file_get_contents($path))->execute();
        die;
        return $this->render('index');
    }


}
