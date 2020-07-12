<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */
/* @var $items_selected array of selected Artifacts */
/* @var $items_artifacts array of Artifacts map */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Elemento de Responsabilidad').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Elementos de Responsabilidad'), 'url' => ['index', 'id'=>$model->role_responsibility_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="role-responsibility-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'items_selected' => $items_selected,
        'items_artifacts' => $items_artifacts,
    ]) ?>

</div>
