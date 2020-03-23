<?php

use yii\db\Migration;

/**
 * Class m190815_140413_create_user_profile
 */
class m190815_140413_create_user_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string(),
            'avatar' => $this->string(),

            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),

        ], $tableOptions);

        $this->addForeignKey('fk_user_profile_user',
            '{{%user_profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('user_profile', Yii::$app->db->schema->getTableNames())) {
            echo "removing foreign key fk_user_profile_user ...";
            $this->dropForeignKey('fk_user_profile_user', '{{%user_profile}}');
            echo "truncating table user_profile ...";
            $this->truncateTable('{{%user_profile}}');
            echo "deleting table user_profile ...";
            $this->dropTable('{{%user_profile}}');
        }
    }
}
