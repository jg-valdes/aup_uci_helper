<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\date\DatePicker;
use kartik\number\NumberControl;
use common\models\GlobalFunctions;
use kartik\datecontrol\DateControl;
use backend\models\Process;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        
            
    <?=
        $form->field($model, "process_id")->widget(Select2::classname(), [
            "data" => Process::getSelectMap(),
            "language" => Yii::$app->language,
            "options" => ["placeholder" => "----", "multiple"=>false],
            "pluginOptions" => [
                "allowClear" => true
            ],
        ]);
    ?>
             
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
          
    <?= 
        $form->field($model, "description")->widget(CKEditor::className(), [
            "preset" => "custom",
            "clientOptions" => [
                "toolbar" => GlobalFunctions::getToolBarForCkEditor(),
            ],
        ])
    ?>
             
    <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>
         
    <?= 
        $form->field($model, "order")->widget(NumberControl::classname(), [
            "maskedInputOptions" => [
                "allowMinus" => false,
                "groupSeparator" => ".",
                "radixPoint" => ",",
                "digits" => 0
            ],
            "displayOptions" => ["class" => "form-control kv-monospace"],
            "saveInputContainer" => ["class" => "kv-saved-cont"]
        ])
    ?> 
             
    <?=
        $form->field($model,"status")->widget(SwitchInput::classname(), [
            "type" => SwitchInput::CHECKBOX,
            "pluginOptions" => [
                "onText"=> Yii::t("backend","Activo"),
                "offText"=> Yii::t("backend","Inactivo")
            ]
        ])
    ?>
             
    <?=
        $form->field($model, "created_at")->widget(DateControl::classname(), [
            "type" => DateControl::FORMAT_DATETIME
        ])
    ?>
             
    <?=
        $form->field($model, "updated_at")->widget(DateControl::classname(), [
            "type" => DateControl::FORMAT_DATETIME
        ])
    ?>
    
</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>
