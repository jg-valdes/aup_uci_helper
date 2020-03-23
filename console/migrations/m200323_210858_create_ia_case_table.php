<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ia_case}}`.
 */
class m200323_210858_create_ia_case_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ia_case}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ia_case}}');
    }
}
