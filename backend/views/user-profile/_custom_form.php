<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use backend\models\auth\UserProfile;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model \backend\models\auth\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="box-body">

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'gender')->widget(SwitchInput::classname(), [
                'pluginOptions' => [
                    'onText' => "Masculino",
                    'offText' => "Femenino"
                ]
            ]); ?>
        </div>
    </div>


    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone_mobile')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'phone_fixed')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'browseIcon' => '<i class="fa fa-camera"></i> ',
                    'browseLabel' => 'Cambiar avatar',
                    'defaultPreviewContent' => '<img src="' . $model->generateFileRoute() . '" class="img-responsive">',
                    'showUpload' => false,
                    'layoutTemplates' => [
                        'main1' => '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                    ],
                ]
            ]);
            ?>
        </div>
    </div>

    <br>

</div>
<div class="box-footer">
    <?= Html::submitButton('<i class="fa fa-pencil"></i> ' . Yii::t('yii', 'Update'), ['class' => 'btn btn-primary btn-flat']) ?>

</div>
<?php ActiveForm::end(); ?>

