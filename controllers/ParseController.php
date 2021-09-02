<?php

namespace app\controllers;

use app\models\DbPost;
use app\models\ListFile;
use app\models\Parse;
use app\models\search\ParseSearch;
use Yii;
use yii\web\Controller;

class ParseController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ParseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * @param $path
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionParse($path)
    {
        $this->parseFile($path);
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
        }
        return $this->redirect('index');
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

}
