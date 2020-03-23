<?php

use yii\db\Migration;

/**
 * Class m190815_140430_create_system_config
 */
class m190815_140430_create_system_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_config}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),

            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),

        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('system_config', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table system_config ...";
            $this->truncateTable('{{%system_config}}');
            echo "deleting table system_config ...";
            $this->dropTable('{{%system_config}}');
        }
    }
}
