<?php

/* @var $this yii\web\View */
/* @var $model backend\models\knn\Metric */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Metrica').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Metricas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="metric-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
