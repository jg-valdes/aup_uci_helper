<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%artifact}}`.
 */
class m200622_110429_create_artifact_table extends Migration
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

        $this->createTable('{{%artifact}}', [
            'id' => $this->primaryKey(),
            'process_id' => $this->integer(10)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'filename' => $this->string(255),
            'order' => $this->integer(10)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_artifact_process',
            '{{%artifact}}',
            'process_id',
            '{{%process}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('artifact', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table artifact ...";
            $this->truncateTable('{{%artifact}}');
            echo "deleting foreignKey fk_artifact_process ...";
            $this->dropForeignKey('fk_artifact_process','{{%artifact}}');
            echo "deleting table artifact ...";
            $this->dropTable('{{%artifact}}');
        }
    }
}
