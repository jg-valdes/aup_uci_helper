<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process}}`.
 */
class m200622_110420_create_process_table extends Migration
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

        $this->createTable('{{%process}}', [
            'id' => $this->primaryKey(),
            'discipline_id' => $this->integer(10)->notNull(),
            'name' => $this->string(255)->notNull(),
            'alias' => $this->string(255),
            'description' => $this->text(),
            'order' => $this->integer(10)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_process_discipline',
            '{{%process}}',
            'discipline_id',
            '{{%discipline}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('process', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table process ...";
            $this->truncateTable('{{%process}}');
            echo "deleting foreignKey fk_process_discipline ...";
            $this->dropForeignKey('fk_process_discipline','{{%process}}');
            echo "deleting table process ...";
            $this->dropTable('{{%process}}');
        }
    }
}
