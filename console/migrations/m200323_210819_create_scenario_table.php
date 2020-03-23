<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%scenario}}`.
 */
class m200323_210819_create_scenario_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%scenario}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%scenario}}');
    }
}
