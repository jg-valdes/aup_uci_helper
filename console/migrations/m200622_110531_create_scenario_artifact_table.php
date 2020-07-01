<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%scenario_artifact}}`.
 */
class m200622_110531_create_scenario_artifact_table extends Migration
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

        $this->createTable('{{%scenario_artifact}}', [
            'id' => $this->primaryKey(),
            'scenario_id' => $this->integer(10)->notNull(),
            'artifact_id' => $this->integer(10)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_scenario_artifact_scenario',
            '{{%scenario_artifact}}',
            'scenario_id',
            '{{%scenario}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->addForeignKey(
            'fk_scenario_artifact_artifact',
            '{{%scenario_artifact}}',
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
        if (in_array('scenario_artifact', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table scenario_artifact ...";
            $this->dropTable('{{%scenario_artifact}}');
        }
    }
}
