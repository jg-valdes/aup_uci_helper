<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%aup_role}}`.
 */
class m200622_110552_create_aup_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%aup_role}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'views' => $this->integer(10)->notNull()->defaultValue(0),
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
        if (in_array('aup_role', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table aup_role ...";
            $this->truncateTable('{{%aup_role}}');
            echo "deleting table aup_role ...";
            $this->dropTable('{{%aup_role}}');
        }
    }
}
