<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use backend\models\Process;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artefactos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box-header">
        <?php 
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-flat margin']);
        }

        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);
        if($model->hasResource()){
            echo Html::a('<i class="fa fa-download"></i> '.Yii::t('backend','Descargar'), ['download', 'id'=>$model->id, 'fromView'=>true], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Descargar')]);
        }

        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a('<i class="fa fa-trash"></i> '.Yii::t('yii','Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat margin',
                'data' => [
                    'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'labelColOptions' => ['style' => 'width: 40%'],
            'attributes' => [
                'id',
                [
                    'attribute'=> 'aup_scenarios',
                    'value'=> $model->getScenariosLink(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'process_id',
                    'value'=> $model->getProcessLink(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'order',
                    'value'=> GlobalFunctions::formatNumber($model->order),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'views',
                    'value'=> GlobalFunctions::getFormattedViewsCount($model->views, true),
                    'format'=> 'html',
                ],

                [
                    'attribute'=> 'downloads',
                    'value'=> GlobalFunctions::getFormattedDownsCount($model->downloads, true),
                    'format'=> 'html',
                ],

                'name',
                [
                    'attribute'=> 'description',
                    'value'=> $model->getDescription(),
                    'format'=> 'html',
                ],

                [
                    'attribute'=> 'filename',
                    'value'=> GlobalFunctions::renderPreviewForIndex($model->getResourceFile(), $model->name),
                    'format' => 'raw',
                ],
                [
                    'attribute'=> 'status',
                    'value'=> GlobalFunctions::getStatusValue($model->status),
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'created_at',
                    'value'=> GlobalFunctions::formatDateToShowInSystem($model->created_at),
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'updated_at',
                    'value'=> GlobalFunctions::formatDateToShowInSystem($model->updated_at),
                    'format'=> 'html',
                ],
                
            ],
        ]) ?>
    </div>
