<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Elemento de Responsabilidad').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Elementos de Responsabilidad'), 'url' => ['index', 'id'=>$model->role_responsibility_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="role-responsibility-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
