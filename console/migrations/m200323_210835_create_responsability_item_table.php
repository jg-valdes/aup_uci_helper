<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%responsability_item}}`.
 */
class m200323_210835_create_responsability_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%responsability_item}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%responsability_item}}');
    }
}
