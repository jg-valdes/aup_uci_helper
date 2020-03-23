<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process}}`.
 */
class m200323_210741_create_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%process}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%process}}');
    }
}
