<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ia_case}}`.
 */
class m200622_110716_create_ia_case_table extends Migration
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

//        $this->createTable('{{%ia_case}}', [
//            'id' => $this->primaryKey(),
//            'scenario_id' => $this->integer(10),
//            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
//            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
//            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
//        ], $tableOptions);

        $this->addForeignKey(
            'fk_ia_case_scenario',
            '{{%ia_case}}',
            'scenario_id',
            '{{%scenario}}',
            'id',
            'CASCADE',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('ia_case', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table ia_case ...";
            $this->truncateTable('{{%ia_case}}');
            echo "deleting foreignKey fk_ia_case_scenario ...";
            $this->dropForeignKey('fk_ia_case_scenario','{{%ia_case}}');
            echo "deleting table ia_case ...";
            $this->dropTable('{{%ia_case}}');
        }
    }
}
