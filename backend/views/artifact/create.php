<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $items_selected array of selected Scenarios */
/* @var $items_scenarios array of Scenarios map */
/* @var $items_responsibilities_selected array of selected RoleResponsibilityItem */
/* @var $items_responsibilities array of RoleResponsibilityItem map */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Artefacto');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artefactos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artifact-create">

    <?= $this->render('_form', [
        'model' => $model,
        'items_selected' => $items_selected,
        'items_scenarios' => $items_scenarios,
        'items_responsibilities_selected' => $items_responsibilities_selected,
        'items_responsibilities' => $items_responsibilities,
    ]) ?>

</div>
