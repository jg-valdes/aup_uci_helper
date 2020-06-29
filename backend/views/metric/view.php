<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\models\knn\Metric */
/* @var $modelItem \backend\models\knn\MetricItem */
/* @var $modelRelation \backend\models\knn\MetricMetricItem */
/* @var $metricItemsDataProvider \yii\data\ActiveDataProvider */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Metricas'), 'url' => ['index']];
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
        <div class="col-md-12 col-lg12 col-xs-12 col-sm-12">
            <?php
            $content1 = $this->render('_tab_general_data', ['model' => $model]);
            $content2 = $this->render('_tab_metric_items', [
                'model' => $model,
                'modelItem'=> $modelItem,
                'modelRelation' => $modelRelation,
                'metricItemsDataProvider' => $metricItemsDataProvider
            ]);

            $items = [
                [
                    'label'=> Yii::t('backend', 'Datos generales'),
                    'content'=>$content1,
                    'active'=>true
                ],
                [
                    'label'=>Yii::t('backend', 'Opciones'),
                    'content'=>$content2,
                ],
            ];

            echo TabsX::widget([
                'items' => $items,
                'position' => TabsX::POS_ABOVE,
                'encodeLabels' => false
            ]);
            ?>

        </div>

    </div>
