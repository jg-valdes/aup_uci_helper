<?php

use yii\db\Migration;
use backend\models\i18n\Message;
use backend\models\i18n\SourceMessage;

/**
 * Class m200606_145559_init_values_translations_crud_faqs
 */
class m200606_145559_init_values_translations_crud_faqs extends Migration
{
    public function safeUp()
    {
        $source_messages = [
            [389, 'backend', 'Grupo de FAQ', 'FAQ Group'],
            [390, 'backend', 'Grupos de FAQ', 'FAQ Groups'],
            [391, 'backend', 'Pregunta', 'Question'],
            [392, 'backend', 'Respuesta', 'Answer'],
            [393, 'backend', 'Groupo', 'Group'],
        ];

        echo "   > Inserting translations into SourceMessage and Message table.\n";
        foreach ($source_messages as $key => $value)
        {
            $source = new SourceMessage();
            $source->id= $value[0];
            $source->category = $value[1];
            $source->message = $value[2];
            if(!$source->save())
            {
                echo 'Error en la traducción: '.$source->id;
            }

            $msg = new Message();
            $msg->id = $value[0];
            $msg->language = 'en';
            $msg->translation = $value[3];
            if(!$msg->save())
            {
                echo 'Error en la traducción: '.$source->id;
            }
        }
    }

    public function safeDown()
    {
        $models = SourceMessage::find()->where('id >= 389 AND id <= 393')->all();

        if($models)
        {
            foreach ($models AS $key => $model)
            {
                $model->delete();
            }
        }

        return true;
    }
}
