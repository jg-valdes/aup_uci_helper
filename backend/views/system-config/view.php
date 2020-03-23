<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\settings\SystemConfig */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' =>'Configuraciones del Sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Nuevo'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Editar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Listar'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(Yii::t('app', 'Eliminar'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => '¿Seguro desea eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'value' => $model->id,
                'visible' => Yii::$app->user->isSuperadmin,
            ],
            [
                'attribute'=> 'name',
                'value'=> \yii\helpers\HtmlPurifier::process($model->name),
                'format'=> 'html',
            ],
            [
                'attribute'=> 'description',
                'value'=> \yii\helpers\HtmlPurifier::process($model->description),
                'format'=> 'raw',
            ],
            [
                'attribute'=> 'json_config',
                'value'=> \yii\helpers\Html::encode($model->json_config),
                'format'=> 'html',
            ],
            'created_at',
            'updated_at',
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
