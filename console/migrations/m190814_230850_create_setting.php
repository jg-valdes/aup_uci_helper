<?php

use yii\db\Migration;

/**
 * Class m190814_230850_create_setting
 */
class m190814_230850_create_setting extends Migration
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

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'seo_keywords' => $this->string(),
            'description' => $this->text()->notNull(),
            'main_logo' => $this->string(),
            'header_logo' => $this->string(),
            'mini_header_logo' => $this->string(),
            'phone' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
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
        if (in_array('setting', Yii::$app->db->schema->getTableNames())) {
            echo "truncating table setting ...";
            $this->truncateTable('{{%setting}}');
            echo "deleting table setting ...";
            $this->dropTable('{{%setting}}');
        }
    }

}
