<?php

use kartik\sortable\Sortable;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $form yii\widgets\ActiveForm */
/* @var $items_responsibilities_selected array of selected RoleResponsibilityItem */
/* @var $items_responsibilities array of RoleResponsibilityItem map */

?>

<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('backend', 'Elementos disponibles') ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?=
                Sortable::widget([
                    'connected' => true,
                    'items' => $items_responsibilities,
                    //'showHandle'=>true,
                    'id'=>'sortable-responsibilities-available',
                    'pluginEvents' => [
                        'sortupdate' => 'function() { 
                              //create the array that hold the positions...
                              var order = []; 
                                                                      
                              //loop trought each li...
                              $("#sortable-responsibilities-select li").each( function(e) {
                                  //add each li position to the array...     
                                  order.push( $(this).attr("data-id") );
                              });
                      
                              // join the array as single variable...
                              var positions = order.join(",");
                              document.getElementById("artifact-aup_responsibilities").value = positions;
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
                <h3 class="box-title"><?= Yii::t('backend', 'Elementos seleccionados') ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?=
                Sortable::widget([
                    'id' => 'sortable-responsibilities-select',
                    'connected' => true,
                    'itemOptions' => ['class' => 'alert alert-info'],
                    'items' => $items_responsibilities_selected,
                    //'showHandle'=>true,
                    'pluginEvents' => [
                        'sortupdate' => 'function() { 
                              //create the array that hold the positions...
                              var order = []; 
                                                                      
                              //loop trought each li...
                              $("#sortable-responsibilities-select li").each( function(e) {
                                  //add each li position to the array...     
                                  order.push( $(this).attr("data-id") );
                              });
                      
                              // join the array as single variable...
                              var positions = order.join(",");
                              document.getElementById("artifact-role_responsibilities").value = positions;
                        }',
                    ],
                ])
                ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <?= $form->field($model, 'role_responsibilities')->hiddenInput(['maxlength' => true])->label(false) ?>
</div>



