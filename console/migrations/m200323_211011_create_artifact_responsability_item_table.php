<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%artifact_responsability_item}}`.
 */
class m200323_211011_create_artifact_responsability_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%artifact_responsability_item}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%artifact_responsability_item}}');
    }
}
