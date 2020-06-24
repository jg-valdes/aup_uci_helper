<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Scenario */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Escenario').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Escenarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="scenario-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
