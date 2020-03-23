<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discipline}}`.
 */
class m200323_210709_create_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%discipline}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%discipline}}');
    }
}
