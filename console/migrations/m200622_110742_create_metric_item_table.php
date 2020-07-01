<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%metric_item}}`.
 */
class m200622_110742_create_metric_item_table extends Migration
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

        $this->createTable('{{%metric_item}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
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
        if (in_array('metric_item', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table metric_item ...";
            $this->truncateTable('{{%metric_item}}');
            echo "deleting table metric_item ...";
            $this->dropTable('{{%metric_item}}');
        }
    }
}
