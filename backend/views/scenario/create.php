<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Scenario */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Escenario');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Escenarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scenario-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
