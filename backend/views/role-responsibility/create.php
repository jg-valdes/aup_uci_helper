<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibility */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Role Responsibility');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Role Responsibilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-responsibility-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
