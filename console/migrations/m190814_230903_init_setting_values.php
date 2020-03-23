<?php

use yii\db\Migration;
use backend\models\settings\Setting;

/**
 * Class m190814_230903_init_setting_values
 */
class m190814_230903_init_setting_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $setting_exist = Setting::findOne(1);

        if(isset($setting_exist) && !empty($setting_exist))
        {
            echo "      > Setting already created, not inserted data again.\n";
        }
        else
        {
            $seeting_model = new Setting();
            $seeting_model->id = 1;
            $seeting_model->address = 'Carretera a San Antonio de los Baños km 1(1/2), Torrens, La Habana, Cuba.';
            $seeting_model->email = 'ecperez@estudiantes.uci.cu';
            $seeting_model->phone= '+53 8888888';
            $seeting_model->mini_header_logo = null;

            $seeting_model->name = 'AUP-UCI Manager';
            $seeting_model->description = 'Sistema para el apoyo a la Gestión de Proyectos';
            $seeting_model->seo_keywords = 'AUP-UCI, Gestión de Proyectos, Artefactos Ingenieriles';


            if($seeting_model->save())
            {
                echo "      > Setting data has been created successfully.\n";
            }
            else
            {
                echo "      > m190513_021114_create_init_values_setting cannot create data for settings table.\n";
                return false;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190814_230903_init_setting_values cannot be reverted.\n";

        return false;
    }
}
