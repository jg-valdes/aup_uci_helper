<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\rbacDB\Role;

/* @var $this yii\web\View */
/* @var $model \backend\models\auth\\UserProfile */
/* @var $modelUser \webvimark\modules\UserManagement\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $isNewUser boolean */

$ajaxEmail = \yii\helpers\Url::to(['/user-profile/ajax-email']);

?>


<div class="user-profile-form">

    <?php $form = ActiveForm::begin([
        'validateOnBlur' => false,
        //'enableClientValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal form-label-left input_mask',
            'id' => $model->formName()
        ]
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6 col-sm-12 col-xs-12" style="margin-right: 3rem;">
                <?= $form->field($model, 'username')->textInput(
                    [
                        'maxlength' => true, 'autocomplete' => 'off',
                        'autofocus' => true,
                        'placeholder' => 'Usuario (hasta 20 caracteres)',
                        'readonly' => ($model->scenario != 'newUser')
                    ])
                    ->label($model->getAttributeLabel("username") . Html::tag("span", " *", ['class' => 'input-required'])); ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Correo (necesario para recuperar contraseña)'])
                    ->label($model->getAttributeLabel("email") . Html::tag("span", " *", ['class' => 'input-required'])); ?>

                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Nombre'])
                    ->label($model->getAttributeLabel("first_name") . Html::tag("span", " *", ['class' => 'input-required'])); ?>

                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Apellidos'])
                    ->label($model->getAttributeLabel("last_name") . Html::tag("span", " *", ['class' => 'input-required'])); ?>

            </div>

            <div class="col-md-5 col-sm-12 col-xs-12">

                <?= $form->field($model, 'file')->widget(\kartik\file\FileInput::className(), [
                    'model' => $model,
                    'attribute' => 'file',
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'showPreview' => !$model->isNewRecord && $model->hasAvatar(),
                        'showCaption' => true,
                        'showRemove' => $model->hasAvatar() || $model->isNewRecord,
                        'initialPreview' => [
                            Html::img($model->generateFileRoute(),
                                ['style' => 'height:160px; width:100%', 'class' => 'img-responsive'])
                        ],
                        'initialPreviewConfig' => [
                            ['caption' => $model->avatar],
                        ],
                        'overwriteInitial' => !$model->isNewRecord && $model->hasAvatar(),
                        'initialCaption' => $model->hasAvatar() ? $model->avatar : "Seleccione un avatar",
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => 'Seleccionar avatar'
                    ],
                    'pluginEvents' => [
                        "fileclear" => "function() { 
                         $.ajax({
                             url: '" . \yii\helpers\Url::to(['/user-profile/remove-image', 'id' => $model->id]) . "',
                             type:'POST',
                             success: function(data) {
                                log('Eliminado');
                                },
                                error: function(data) {
                               }
});
                         }"
                    ],
                ]); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong style="color: #73879C">
                            <span class="glyphicon glyphicon-th"></span>
                            Roles <?= Html::tag("span", " *", ['class' => 'input-required']); ?>
                        </strong>
                    </div>
                    <div class="panel-body">

                        <?php foreach (Role::getAvailableRoles(true) as $aRole): ?>
                            <label>
                                <?php $isChecked = $model->isNewRecord ? false :
                                    in_array($aRole['name'], \yii\helpers\ArrayHelper::map(Role::getUserRoles($model->user_id), 'name', 'name')) ? 'checked' : '' ?>


                                <?php if (Yii::$app->getModule('user-management')->userCanHaveMultipleRoles): ?>
                                    <input type="checkbox" <?= $isChecked ?> name="roles[]"
                                           value="<?= $aRole['name'] ?>">

                                <?php else: ?>
                                    <input type="radio" <?= $isChecked ?> name="roles" value="<?= $aRole['name'] ?>">

                                <?php endif; ?>

                                <?= $aRole['description'] ?>
                            </label>

                            <?= \webvimark\modules\UserManagement\components\GhostHtml::a(
                            '<span class="glyphicon glyphicon-edit"></span>',
                            ['/user-management/role/view', 'id' => $aRole['name']],
                            ['target' => '_blank']
                        ) ?>
                            <br/>
                        <?php endforeach ?>
                    </div>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-6">
                    <?= $form->field($model, 'status')->widget(\kartik\switchinput\SwitchInput::classname(), [
                        'name' => 'status',
                        'readonly' => !User::hasPermission("ManageUserProfiles"),
                        'pluginOptions' => ['size' => 'medium',
                            'onText' => 'Activo',
                            'offText' => 'Inactivo',
                        ],
                    ])->label($model->getAttributeLabel('status')); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-6">
                    <?= $form->field($model, 'gender')->widget(\kartik\switchinput\SwitchInput::classname(), [
                        'name' => 'status',
                        'readonly' => !User::hasPermission("ManageUserProfiles"),
                        'pluginOptions' => ['size' => 'medium',
                            'onText' => 'Masculino',
                            'offText' => 'Femenino',
                        ],
                    ])->label($model->getAttributeLabel('gender')); ?>

                </div>

            </div>

        </div>

        <div class="col-md-12">
            <?php if ($model->scenario == 'newUser'): ?>
                <div class="col-md-6 col-sm-12 col-xs-12" style="margin-right: 3rem;">
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'placeholder' => 'Contraseña'])
                        ->label($model->getAttributeLabel("password") . Html::tag("span", " *", ['class' => 'input-required'])); ?>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'placeholder' => 'Repetir contraseña'])
                        ->label($model->getAttributeLabel("repeat_password") . Html::tag("span", " *", ['class' => 'input-required'])); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <div class="col-md-6 col-sm-12 col-xs-12" style="margin-right: 3rem;">
                <?= $form->field($model, 'phone_mobile')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => $model->getAttributeLabel("phone_mobile")])
                    ->label($model->getAttributeLabel("phone_mobile") . Html::tag("span", " *", ['class' => 'input-required'])); ?>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12">
                <?= $form->field($model, 'phone_fixed')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => $model->getAttributeLabel("phone_fixed")])
                    ->label($model->getAttributeLabel("phone_fixed") . Html::tag("span", " *", ['class' => 'input-required'])); ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-12">
                <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Entre su dirección particular'])
                    ->label($model->getAttributeLabel("address") . Html::tag("span", " *", ['class' => 'input-required'])); ?>
            </div>
            <div class="col-md-12">
                    <?=
                    $form->field($model, "coupons")->widget(\kartik\select2\Select2::classname(), [
                        "data" => \backend\models\business\Coupon::getAvailableCouponsMap($model->user_id),
                        "options" => ["placeholder" => "Cupones", "multiple"=>true],
                        "pluginOptions" => [
                            "allowClear" => true,
                            "multiple" => true
                        ],
                    ]);?>
            </div>
        </div>


    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?= Html::submitButton('<span class="fa fa-check"></span> ' . ($model->isNewRecord ? 'Insertar' : 'Actualizar'), ['class' => 'btn btn-primary']); ?>
            <?= Html::a('<span class="fa fa-list-alt"></span> Ver lista', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
