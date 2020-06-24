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
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\business\Discipline;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Discipline */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-6 col-xs-6">
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
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
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
        <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
            <?=
            $form->field($model, "description")->widget(CKEditor::className(), [
                "preset" => "custom",
                "clientOptions" => [
                    "toolbar" => GlobalFunctions::getToolBarForCkEditor(),
                ],
            ])
            ?>
        </div>
    </div>

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

