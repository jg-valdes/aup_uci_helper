<?php

/* @var $this yii\web\View */
/* @var $model backend\models\knn\MetricItem */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Metric Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Metric Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metric-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
