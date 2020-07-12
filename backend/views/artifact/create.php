<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $items_selected array of selected Scenarios */
/* @var $items_scenarios array of Scenarios map */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Artefacto');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artefactos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artifact-create">

    <?= $this->render('_form', [
        'model' => $model,
        'items_selected' => $items_selected,
        'items_scenarios' => $items_scenarios,
    ]) ?>

</div>
