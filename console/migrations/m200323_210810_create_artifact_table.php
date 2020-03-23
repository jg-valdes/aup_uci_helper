<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%artifact}}`.
 */
class m200323_210810_create_artifact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%artifact}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%artifact}}');
    }
}
