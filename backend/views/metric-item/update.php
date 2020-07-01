<?php

/* @var $this yii\web\View */
/* @var $model backend\models\knn\MetricItem */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Metric Item').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Metric Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="metric-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
