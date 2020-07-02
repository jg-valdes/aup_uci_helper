<?php

use kartik\number\NumberControl;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model \backend\models\business\RoleResponsibility */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="metric-item-form">

        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-6 col-xs-6">
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
        </div>
    </div>
