<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \backend\models\auth\UserProfile $model
 */

$this->title = 'Cambiando contraseña para usuario: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Perfiles de usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cambiando contraseña';
?>
<div class="user-update">

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="user-form">

                <?php $form = ActiveForm::begin([
                    'id' => 'user',
                    'layout' => 'horizontal',
                ]); ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'maxlength' => true,
                    'placeholder' => 'Mínimo (4 caracteres)',
                    'autocomplete' => 'off']) ?>

                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']) ?>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?php if ($model->isNewRecord): ?>
                            <?= Html::submitButton(
                                '<span class="glyphicon glyphicon-plus-sign"></span> Actualizar',
                                ['class' => 'btn btn-success']
                            ) ?>
                        <?php else: ?>
                            <?= Html::submitButton(
                                '<span class="glyphicon glyphicon-ok"></span> Guardar',
                                ['class' => 'btn btn-primary']
                            ) ?>
                            <?= Html::a('Cancelar', ['view', 'id' => $model->id], ['class' => 'btn btn-default']); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>
