<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Scenario */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Scenario').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Scenarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="scenario-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
