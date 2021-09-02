<?php

use app\models\Parse;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%parse}}`.
 */
class m210901_212641_create_parses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Parse::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'format' => $this->integer(),
            'file' => $this->string(255),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Parse::tableName());
    }
}
