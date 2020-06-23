<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use backend\models\Discipline;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Process */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box-header">
        <?php 
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-flat margin']);
        }

        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);

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
                    'attribute'=> 'discipline_id',
                    'value'=> (isset($model->discipline->name) && !empty($model->discipline->name))? $model->discipline->name : null,
                    'format'=> 'html',
                ],
        
                'name',
                'alias',
                [
                    'attribute'=> 'description',
                    'value'=> $model->description,
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'order',
                    'value'=> GlobalFunctions::formatNumber($model->order),
                    'format'=> 'html',
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