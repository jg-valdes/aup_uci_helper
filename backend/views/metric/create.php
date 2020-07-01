<?php

/* @var $this yii\web\View */
/* @var $model backend\models\knn\Metric */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Métrica');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Metricas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metric-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
