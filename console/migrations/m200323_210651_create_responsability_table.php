<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%responsability}}`.
 */
class m200323_210651_create_responsability_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%responsability}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%responsability}}');
    }
}
