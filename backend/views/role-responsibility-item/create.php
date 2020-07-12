<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */
/* @var $items_selected array of selected Artifacts */
/* @var $items_artifacts array of Artifacts map */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Elemento de Responsabilidad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Elementos de Responsabilidad'), 'url' => ['index', 'id'=>$model->role_responsibility_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-responsibility-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'items_selected' => $items_selected,
        'items_artifacts' => $items_artifacts,
    ]) ?>

</div>
