<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibility */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Role Responsibility').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Role Responsibilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="role-responsibility-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
