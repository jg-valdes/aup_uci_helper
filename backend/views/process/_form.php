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
use backend\models\Discipline;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Process */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">

        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">

        </div>
        <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">

        </div>
    </div>

    <?=
        $form->field($model, "discipline_id")->widget(Select2::classname(), [
            "data" => Discipline::getSelectMap(),
            "language" => Yii::$app->language,
            "options" => ["placeholder" => "----", "multiple"=>false],
            "pluginOptions" => [
                "allowClear" => true
            ],
        ]);
    ?>
             
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
         
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
          
    <?= 
        $form->field($model, "description")->widget(CKEditor::className(), [
            "preset" => "custom",
            "clientOptions" => [
                "toolbar" => GlobalFunctions::getToolBarForCkEditor(),
            ],
        ])
    ?>
             
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
             

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

