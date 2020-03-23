<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $loginError boolean true if login has errors */
/* @var $model \webvimark\modules\UserManagement\models\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\settings\Setting;

$this->title = 'Administración';

$base_url = Yii::$app->homeUrl;

?>

<div id="login-container">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['autocomplete' => 'off', 'class' => 'login100-form validate-form flex-sb flex-w'],
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => "{input}\n{error}",
        ],
    ]) ?>

    <span class="login100-form-title p-b-32 text-center">
        <?= Html::img(Setting::getUrlLogoBySettingAndType(Setting::HEADER_LOGO), ['style'=>'width: 70%']) ?>
    </span>
    <span class="login100-form-title p-b-32 text-center"><?= $this->title; ?></span>

    <?php

    if ($loginError){ ?>
    <span class="login100-form-title p-b-16" style="font-size: 15px;">
        <div class="alert alert-danger">
            Usuario o contrase&ntilde;a incorrectos!!
        </div>
    </span>
    <?php } ?>

    <span class="txt1 p-b-11">Usuario</span>
    <div class="wrap-input100 validate-input m-b-36" data-validate="Usuario es requirido">
        <input autocomplete="off" id="loginform-username" name="LoginForm[username]" class="input100" type="text">
        <span class="focus-input100"></span>
    </div>

    <span class="txt1 p-b-11">Contrase&ntilde;a</span>
    <div class="wrap-input100 validate-input m-b-12" data-validate="Contraseña es requirida">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
        <input class="input100" type="password" autocomplete="off" id="loginform-password" name="LoginForm[password]">
        <span class="focus-input100"></span>
    </div>

    <div class="flex-sb-m w-full p-b-48">
        <div class="contact100-form-checkbox">
            <input class="input-checkbox100" id="loginform-rememberMe" name="LoginForm[rememberMe]" type="checkbox">
            <label class="label-checkbox100" for="loginform-rememberMe">
                Recordarme
            </label>
        </div>

        <div>

        </div>
    </div>

    <div class="container-login100-form-btn">
        <?= Html::submitButton(
            'Acceder',
            ['class' => 'login100-form-btn', 'id' => 'login-btn']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

