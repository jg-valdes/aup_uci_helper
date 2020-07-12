<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\number\NumberControl;
use common\models\GlobalFunctions;
use backend\models\business\Process;
use kartik\select2\Select2;
use backend\models\business\Artifact;
use kartik\sortable\Sortable;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $form yii\widgets\ActiveForm */
/* @var $items_selected array of selected Scenarios */
/* @var $items_scenarios array of Scenarios map */

?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <?=
            $form->field($model, "process_id")->widget(Select2::classname(), [
                "data" => Process::getSelectMap(),
                "language" => Yii::$app->language,
                "options" => ["placeholder" => "----", "multiple"=>false],
                "pluginOptions" => [
                    "allowClear" => true
                ],
                "pluginEvents" => [
                    'change' => new \yii\web\JsExpression("function(e){
                        $.ajax({
                            type: 'POST',
                            url: '/artifact/get-last-order?processId=' + $(this).val(),
                            success: function(data){
                                if(data.hasOwnProperty('order')){
                                    $('#artifact-order-disp').val(data.order);
                                    $('#artifact-order').val(data.order);
                                }
                            },
                            error: function(error){
                                if(error.hasOwnProperty('responseText')){
                                    alert(error.responseText);
                                }else{
                                    window.location.reload();
                                }
                            }
                        });
                    }
                    ")
                    ]
            ]);
            ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
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
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
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
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <?=
            $form->field($model, "filename")->widget(FileInput::classname(), [
                "language" => Yii::$app->language,
                'pluginOptions'=> GlobalFunctions::getConfigFileInputWithPreview($model->getResourceFile(), $model->name, Artifact::getAllowedExtensions())
            ]);
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

    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('backend','Escenarios disponibles') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    Sortable::widget([
                        'connected'=>true,
                        'items'=> $items_scenarios,
                        //'showHandle'=>true,
                        'pluginEvents' => [
                            'sortupdate' => 'function() { 
                              //create the array that hold the positions...
                              var order = []; 
                                                                      
                              //loop trought each li...
                              $("#sortable-select li").each( function(e) {
                                  //add each li position to the array...     
                                  order.push( $(this).attr("data-id") );
                              });
                      
                              // join the array as single variable...
                              var positions = order.join(",");
                              document.getElementById("artifact-aup_scenarios").value = positions;
                        }',
                        ],
                    ])
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('backend','Escenarios que aplican') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    Sortable::widget([
                        'id' => 'sortable-select',
                        'connected'=>true,
                        'itemOptions'=>['class'=>'alert alert-info'],
                        'items'=> $items_selected,
                        //'showHandle'=>true,
                        'pluginEvents' => [
                            'sortupdate' => 'function() { 
                              //create the array that hold the positions...
                              var order = []; 
                                                                      
                              //loop trought each li...
                              $("#sortable-select li").each( function(e) {
                                  //add each li position to the array...     
                                  order.push( $(this).attr("data-id") );
                              });
                      
                              // join the array as single variable...
                              var positions = order.join(",");
                              document.getElementById("artifact-aup_scenarios").value = positions;
                        }',
                        ],
                    ])
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

<?= $form->field($model, 'aup_scenarios')->hiddenInput(['maxlength' => true])->label(false) ?>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
  //create the array that hold the positions...
  var order = []; 
                                          
  //loop trought each li...
  $("#sortable-select li").each( function(e) {
      //add each li position to the array...     
      order.push( $(this).attr("data-id") );
  });

  // join the array as single variable...
  var positions = order.join(",");
  document.getElementById("artifact-aup_scenarios").value = positions;
                 
JS;

$this->registerJs($script);
?>