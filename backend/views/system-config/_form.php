<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\settings\SystemConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="box-body">

        <div class="row">
            <div class="col-md-6">
                <?=
                $form->field($model, "name")->widget(\kartik\select2\Select2::classname(), [
                    "data" => $model->getSettingsConfigNameForCreation(),
                    "language" => "es",
                    "options" => ["placeholder" => "----", "multiple"=>false],
                    "pluginOptions" => [
                        "allowClear" => true
                    ],
                    "pluginEvents" => [
                        'change' => new \yii\web\JsExpression("function(e){
                        console.log($(this).val());
                        $.ajax({
                            type: 'POST',
                            url: '/admin/system-config/get_setting_params?setting_name=' + $(this).val(),
                            success: function(data){
                                console.log(data);
                                if(data.hasOwnProperty('params')){
                                    let html = '';
                                    for(let i in data.params){
                                        html += '<code>' + data.params[i] + '</code>&nbsp;';
                                    }
                                    $('#setting-params-list').html(html);
                                    $('#setting-params').show();
                                }
                            },
                            error: function(error){
                            console.log(error);
                            }
                        });
                    }
                    ")
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model,"status")->widget(\kartik\widgets\SwitchInput::classname(), [
                    "type" => \kartik\widgets\SwitchInput::CHECKBOX,
                    "pluginOptions" => [
                        "onText"=> Yii::t("app","Activo"),
                        "offText"=> Yii::t("app","Inactivo")
                    ]
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div id="setting-params" style="display: <?= $model->isNewRecord ? 'none' : 'inline-block'; ?>;">
                    <p>
                        <strong><?= Yii::t("app", "Parámetros disponibles");?></strong>:
                    <div id="setting-params-list">
                        <?php if(!$model->isNewRecord){
                            foreach (\backend\models\settings\SystemConfig::getSettingsConfigParamsMap()[$model->name] AS $param){
                                echo "<code>{$param}</code>&nbsp;";
                            }
                        }?>
                    </div>
                    </p>
                </div>
                <?=
                $form->field($model, "json_config")->textarea([
                    "rows" => 11,
                    "style" => "resize: none",
                ])
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'description')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                    "options" => ["rows" => 7, 'maxlength' => true],
                    "preset" => "basic",
                    "clientOptions" => [
                        'filebrowserUploadUrl' => \yii\helpers\Url::to(['/site/ckeditorupload']),
                    ]
                ]) ?>
            </div>
        </div>

    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('app','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
        <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('app','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('app','Cancelar')]) ?>
    </div>

<?php ActiveForm::end(); ?>