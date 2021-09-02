<?php

namespace app\controllers;

use app\models\DbPost;
use app\models\JoinParse;
use app\models\ListFile;
use app\models\Parse;
use app\models\search\ParseSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ParseController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex($format = Parse::FORMAT_XML)
    {
        $searchModel = new ParseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $format);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * @return string
     */
    public function actionJoin($format = Parse::FORMAT_XML)
    {
        $parses = Parse::find()->where(['format' => $format])->all();
        $parses = ArrayHelper::map($parses, 'id', 'name');
        $model = new JoinParse($format);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->joinFileParse()) {
            Yii::$app->session->setFlash('success', "Success join.");
            return $this->redirect('join');
        }

        return $this->render('join', compact('parses', 'model'));
    }

    /**
     * @param $path
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionParse($path)
    {
        if ($this->parseFile($path)) {
            Yii::$app->session->setFlash('success', "Success parse.");
        }

        return $this->redirect('index');
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionParseAll()
    {
        $list_files = ListFile::getFiles();
        if (!empty($list_files)) {
            foreach ($list_files as $file_path) {
                $this->parseFile($file_path);
            }
            Yii::$app->session->setFlash('success', "Success parse.");
        }

        return $this->redirect('index');
    }

    /**
     * @param $id
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        if ($model = $this->findModel($id)) {
            $path = __DIR__ . "/../runtime/parsed/" . Parse::getFormats()[$model->format] . "/{$model->file}";
            if (is_file($path)) {
                return Yii::$app->response->sendFile($path);
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $path = __DIR__ . "/../runtime/parsed/" . Parse::getFormats()[$model->format] . "/{$model->file}";
            if (is_file($path)) {
                unlink($path);
            }
            $model->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $path
     * @return bool
     * @throws \yii\db\Exception
     */
    private function parseFile($path)
    {
        if (file_exists($path)) {
            $db_post = new DbPost();
            $db_posts = $db_post->getListPosts($path);
            $parse = new Parse(basename($path, '.sql'));
            $parse->parse($db_posts);
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return Parse|null
     */
    protected function findModel($id)
    {
        if (($model = Parse::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
