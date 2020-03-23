<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \backend\models\UserProfile */
/* @var $modelUser \webvimark\modules\UserManagement\models\User */
/* @var $isNewUser boolean */
/* @var $isFromIndex boolean true if request is from index, false if is from view */

$this->title = 'Crear perfil de usuario';
$this->params['breadcrumbs'][] = ['label' => 'Perfiles de usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
