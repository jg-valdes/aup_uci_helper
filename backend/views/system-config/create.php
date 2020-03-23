<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\settings\SystemConfig */

$this->title = 'Registrar Configuración del Sistema';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones del Sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
