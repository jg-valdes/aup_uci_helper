<?php


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \backend\models\auth\ProfileChangeOwnPasswordForm $model
 */

$this->title = "Cambiar contraseña";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="change-own-password">


    <div class="panel panel-default">
        <div class="panel-body">

            <div class="user-form">

                <?php $form = ActiveForm::begin([
                    'id' => 'user',
                    'layout' => 'horizontal',
                    'validateOnBlur' => true,
                ]); ?>

                <?php if ($model->scenario != 'restoreViaEmail'): ?>
                    <?= $form->field($model, 'current_password')->passwordInput(['maxlength' => true,
                        'autocomplete' => 'off']) ?>

                <?php endif; ?>

                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,
                    'placeholder' => 'Mínimo (4 caracteres)',
                    'autocomplete' => 'off']) ?>

                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']) ?>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= Html::submitButton(
                            '<span class="glyphicon glyphicon-ok"></span> Cambiar',
                            ['class' => 'btn btn-primary']
                        ) ?>

                        <?= Html::a('Cancelar', ['/site/index'], ['class' => 'btn btn-default']); ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>