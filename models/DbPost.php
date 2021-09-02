<?php

namespace app\models;

use Yii;
use yii\base\Model;

class DbPost extends Model
{
    private $db_name;

    /**
     * Parser constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->db_name = $this->getDsnAttribute('dbname', $_ENV['DB_DSN_TEMP']);
        parent::__construct($config);
    }

    /**
     * @param $file_path
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function getListPosts($file_path)
    {
        Yii::$app->db2->createCommand(file_get_contents($file_path))->execute();
        $list_tables = Yii::$app->db2->createCommand('SELECT TABLE_NAME FROM  INFORMATION_SCHEMA.PARTITIONS WHERE TABLE_SCHEMA = "' . $this->db_name . '"AND TABLE_NAME LIKE "%_posts" ')->queryAll();
        $sql_get_posts = '';
        foreach ($list_tables as $table) {
            $sql_get_posts .= 'SELECT * FROM `' . $table['TABLE_NAME'] . '` UNION ';
        }
        $posts = Yii::$app->db2->createCommand(substr($sql_get_posts, 0, -7))->queryAll();
        $this->clearDb();
        return $posts;
    }


    /**
     * @return bool
     */
    private function clearDb()
    {
        $list_tables = Yii::$app->db2->createCommand('SELECT table_name FROM information_schema.tables WHERE table_schema = "' . $this->db_name . '";')->queryAll();
        foreach ($list_tables as $table) {
            Yii::$app->db2->createCommand('DROP TABLE `' . $table['TABLE_NAME'] . '` ')->execute();
        };
        return true;
    }

    /**
     * @param $name
     * @param $dsn
     * @return mixed|null
     */
    private function getDsnAttribute($name, $dsn)
    {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }
}
