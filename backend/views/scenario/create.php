<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Scenario */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Scenario');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Scenarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scenario-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
