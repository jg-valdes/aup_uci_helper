<?php

use webvimark\modules\UserManagement\UserManagementModule;

/**
 * @var yii\web\View $this
 */

$this->title = "Cambiar contraseña";
$this->params['breadcrumbs'][] = ['label' => 'Inicio', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-own-password-success">

	<div class="alert alert-success text-center">
		<?= UserManagementModule::t('back', 'Password has been changed') ?>
	</div>

</div>
