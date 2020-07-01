<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%case_metric}}`.
 */
class m200622_110812_create_case_metric_table extends Migration
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

        $this->createTable('{{%case_metric}}', [
            'id' => $this->primaryKey(),
            'metric_id' => $this->integer(10)->notNull(),
            'ia_case_id' => $this->integer(10)->notNull(),
            'metric_item_id' => $this->integer(10)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_case_metric_ia_case',
            '{{%case_metric}}',
            'ia_case_id',
            '{{%ia_case}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'fk_case_metric_metric',
            '{{%case_metric}}',
            'metric_id',
            '{{%metric}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'fk_case_metric_metric_item',
            '{{%case_metric}}',
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
        if (in_array('case_metric', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table case_metric ...";
            $this->dropTable('{{%case_metric}}');
        }
    }
}
