<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%metric_metric_item}}`.
 */
class m200622_110759_create_metric_metric_item_table extends Migration
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

        $this->createTable('{{%metric_metric_item}}', [
            'id' => $this->primaryKey(),
            'metric_id' => $this->integer(10)->notNull(),
            'metric_item_id' => $this->integer(10)->notNull(),
            'weight' => $this->double()->notNull()->defaultValue(1),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_metric_metric_item_metric',
            '{{%metric_metric_item}}',
            'metric_id',
            '{{%metric}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'fk_metric_metric_item_metric_item',
            '{{%metric_metric_item}}',
            'metric_item_id',
            '{{%metric_item}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('metric_metric_item', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table metric_metric_item ...";
            $this->dropTable('{{%metric_metric_item}}');
        }
    }
}
