<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use common\models\GlobalFunctions;
use backend\models\business\RoleResponsibilityItem;
use kartik\sortable\Sortable;

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $items_selected array of selected Artifacts */
/* @var $items_artifacts array of Artifacts map */

?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <?= $form->field($model, "role_responsibility_id")->hiddenInput()->label(false);?>
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
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
        <div class="col-md-9 col-lg-9 col-xl-9 col-sm-12 col-xs-12">
            <?=
            $form->field($model, "description")->widget(CKEditor::className(), [
                "preset" => "custom",
                "clientOptions" => [
                    "toolbar" => GlobalFunctions::getToolBarForCkEditor(true),
                ],
            ])
            ?>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
            <?=
            $form->field($model, "filename")->widget(FileInput::classname(), [
                "language" => Yii::$app->language,
                'pluginOptions'=> GlobalFunctions::getConfigFileInputWithPreview($model->getResourceFile(), $model->name, RoleResponsibilityItem::getAllowedExtensions())
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('backend','Artefactos disponibles') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?=
                    Sortable::widget([
                        'connected'=>true,
                        'items'=> $items_artifacts,
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
                              document.getElementById("roleresponsibilityitem-artifacts").value = positions;
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
                    <h3 class="box-title"><?= Yii::t('backend','Artefactos seleccionados') ?></h3>
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
                              document.getElementById("roleresponsibilityitem-artifacts").value = positions;
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

    <?= $form->field($model, 'artifacts')->hiddenInput(['maxlength' => true])->label(false) ?>
</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index', 'id'=>$model->role_responsibility_id], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
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
  document.getElementById("roleresponsibilityitem-artifacts").value = positions;
                 
JS;

$this->registerJs($script);
?>