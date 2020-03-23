<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%scenario_artifact}}`.
 */
class m200323_210931_create_scenario_artifact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%scenario_artifact}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%scenario_artifact}}');
    }
}
