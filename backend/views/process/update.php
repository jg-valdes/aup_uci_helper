<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Process */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Proceso').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Procesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="process-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
