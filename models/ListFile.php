<?php

namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\FileHelper;

class ListFile extends Model
{
    public $files;

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'sql', 'checkExtensionByMimeType' => false, 'maxFiles' => 10],
        ];
    }

    /**
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->saveAs('list-files/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return ArrayDataProvider
     */
    public static function getDataProviderFiles()
    {
        $files = [];
        foreach (self::getFiles() as $file) {
            $files[] = ['name' => basename($file), 'path' => $file];
        }
        $provider = new ArrayDataProvider([
            'allModels' => $files,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $provider;
    }

    /**
     * @return array
     */
    public static function getFiles()
    {
        return FileHelper::findFiles("list-files", ['only' => ['*.sql']]);
    }
}
