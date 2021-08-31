<?php

namespace app\controllers;

use app\models\ListFile;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class ListFileController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = ListFile::getDataProviderFiles();

        return $this->render('index', compact('dataProvider'));
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new ListFile();
        if (Yii::$app->request->isPost) {
            $model->files = UploadedFile::getInstances($model, 'files');
            if ($model->upload()) {
                return $this->redirect(Url::toRoute('index'));
            }
        }
        return $this->render('add', compact('model'));
    }

    /**
     * @param $path
     * @return \yii\web\Response
     */
    public function actionDelete($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


}
