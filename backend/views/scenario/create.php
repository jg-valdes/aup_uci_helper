<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Scenario */
/* @var $items_selected array of selected Artifacts */
/* @var $items_artifacts array of Artifacts map */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Escenario');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Escenarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scenario-create">

    <?= $this->render('_form', [
        'model' => $model,
        'items_selected' => $items_selected,
        'items_artifacts' => $items_artifacts,
    ]) ?>

</div>
