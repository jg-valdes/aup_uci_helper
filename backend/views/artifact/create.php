<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Artefacto');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artefactos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artifact-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
