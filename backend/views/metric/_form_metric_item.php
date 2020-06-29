<?php

use kartik\number\NumberControl;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $item \backend\models\knn\MetricItem */
/* @var $relation \backend\models\knn\MetricMetricItem */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="metric-item-form">

        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                <?= $form->field($item, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-6 col-xs-6">
                <?=
                $form->field($relation, "weight")->widget(NumberControl::classname(), [
                    "maskedInputOptions" => [
                        "allowMinus" => false,
                        "groupSeparator" => ".",
                        "radixPoint" => ",",
                        "digits" => 2
                    ],
                    "displayOptions" => ["class" => "form-control kv-monospace"],
                    "saveInputContainer" => ["class" => "kv-saved-cont"]
                ])
                ?>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-6 col-xs-6">
                <?=
                $form->field($item,"status")->widget(SwitchInput::classname(), [
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
