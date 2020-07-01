<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%role_responsibility}}`.
 */
class m200622_110622_create_role_responsibility_table extends Migration
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

        $this->createTable('{{%role_responsibility}}', [
            'id' => $this->primaryKey(),
            'aup_role_id' => $this->integer(10)->notNull(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_role_responsibility_aup_role',
            '{{%role_responsibility}}',
            'aup_role_id',
            '{{%aup_role}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('role_responsibility', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table role_responsibility ...";
            $this->truncateTable('{{%role_responsibility}}');
            echo "deleting foreignKey fk_role_responsibility_aup_role ...";
            $this->dropForeignKey('fk_role_responsibility_aup_role','{{%role_responsibility}}');
            echo "deleting table role_responsibility ...";
            $this->dropTable('{{%role_responsibility}}');
        }
    }
}
