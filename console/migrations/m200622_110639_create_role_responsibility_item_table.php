<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%role_responsibility_item}}`.
 */
class m200622_110639_create_role_responsibility_item_table extends Migration
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

        $this->createTable('{{%role_responsibility_item}}', [
            'id' => $this->primaryKey(),
            'role_responsibility_id' => $this->integer(10)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'filename' => $this->string(255),
            'downloads' => $this->integer(10)->notNull()->defaultValue(0),
            'views' => $this->integer(10)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'role_fk_role_responsibility_item_responsibility',
            '{{%role_responsibility_item}}',
            'role_responsibility_id',
            '{{%role_responsibility}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('role_responsibility_item', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table role_responsibility_item ...";
            $this->truncateTable('{{%role_responsibility_item}}');
            echo "deleting foreignKey role_fk_role_responsibility_item_responsibility ...";
            $this->dropForeignKey('role_fk_role_responsibility_item_responsibility','{{%role_responsibility_item}}');
            echo "deleting table role_responsibility_item ...";
            $this->dropTable('{{%role_responsibility_item}}');
        }
    }
}
