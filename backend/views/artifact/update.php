<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Artefacto').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artefactos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="artifact-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
