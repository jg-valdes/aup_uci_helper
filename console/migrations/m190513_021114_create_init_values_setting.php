<?php

use yii\db\Migration;
use backend\models\settings\Setting;
use backend\models\settings\SettingLang;

class m190513_021114_create_init_values_setting extends Migration
{
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
            $seeting_model->address = 'Dirección';
            $seeting_model->email = 'ecperez@estudiantes.uci.cu';
            $seeting_model->phone= '+53 5555555';
            $seeting_model->mini_header_logo = null;
            $seeting_model->language = 'es';
            $seeting_model->name = 'Predictor AUP-UCI';
            $seeting_model->seo_keywords = 'AUP-UCI, Metodología de desarrollo de software';
            $seeting_model->description = 'Sistema para el apoyo documental sobre la metodología de desarrollo de software AUPvUCI.';

            if($seeting_model->save())
            {
                echo "      > Setting ES inserted sussessfully.\n";
            }
            else
            {
                echo "      > Error with Setting ES.\n";
                return false;
            }

            $setting_created = Setting::findOne(1);
            if($setting_created)
            {
                $english = new SettingLang();
                $english->setting_id = 1;
                $english->name = 'AUP-UCI Predictor';
                $english->seo_keywords = 'AUP-UCI, Software Development Methodology';
                $english->description = 'System for support references about UCI development Software Methodology.';
                $english->language = 'en';

                if($english->save())
                {
                    echo "      > Setting EN inserted successfully.\n";
                }
                else
                {
                    echo "      > Error with Setting EN.\n";
                    return false;
                }
            }
        }

    }

    public function safeDown()
    {
        echo "      > m190513_021114_create_init_values_setting revert when drop table.\n";

    }
}