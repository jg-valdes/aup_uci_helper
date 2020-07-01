<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Process */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Proceso');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Procesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
