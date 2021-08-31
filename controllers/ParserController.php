<?php

namespace app\controllers;

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

        die;
        return $this->render('index');
    }


}
