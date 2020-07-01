<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%artifact_responsibility_item}}`.
 */
class m200622_110651_create_artifact_responsibility_item_table extends Migration
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

        $this->createTable('{{%artifact_responsibility_item}}', [
            'id' => $this->primaryKey(),
            'artifact_id' => $this->integer(10)->notNull(),
            'role_responsibility_item_id' => $this->integer(10)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_artifact_responsibility_item_role_responsibility_item',
            '{{%artifact_responsibility_item}}',
            'role_responsibility_item_id',
            '{{%role_responsibility_item}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'fk_artifact_responsibility_item_artifact',
            '{{%artifact_responsibility_item}}',
            'artifact_id',
            '{{%artifact}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('artifact_responsibility_item', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table artifact_responsibility_item ...";
            $this->dropTable('{{%artifact_responsibility_item}}');
        }
    }
}
