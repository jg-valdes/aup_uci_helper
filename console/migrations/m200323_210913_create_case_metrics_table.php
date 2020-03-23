<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%case_metrics}}`.
 */
class m200323_210913_create_case_metrics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%case_metrics}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%case_metrics}}');
    }
}
