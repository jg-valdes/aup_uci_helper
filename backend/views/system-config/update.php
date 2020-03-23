<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\settings\SystemConfig */

$this->title =  'Editar Configuración del Sistema:'. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones del Sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="system-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
