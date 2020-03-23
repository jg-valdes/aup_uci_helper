<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%metric}}`.
 */
class m200323_210700_create_metric_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%metric}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%metric}}');
    }
}
