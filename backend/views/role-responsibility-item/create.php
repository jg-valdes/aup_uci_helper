<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Role Responsibility Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Role Responsibility Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-responsibility-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
