<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use backend\models\Scenario;

/* @var $this yii\web\View */
/* @var $model backend\models\knn\IaCase */

$controllerId = '/' . $this->context->uniqueId . '/';
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Base de Casos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-header">
    <?php

    echo Html::a('<i class="fa fa-remove"></i> ' . Yii::t('backend', 'Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Cancelar')]);

    if (Helper::checkRoute($controllerId . 'delete')) {
        echo Html::a('<i class="fa fa-trash"></i> ' . Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat margin',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]);
    }
    ?>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'labelColOptions' => ['style' => 'width: 40%'],
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'scenario_id',
                        'value' => $model->getScenarioLink(),
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'status',
                        'value' => GlobalFunctions::getStatusValue($model->status),
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'created_at',
                        'value' => GlobalFunctions::formatDateToShowInSystem($model->created_at),
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'updated_at',
                        'value' => GlobalFunctions::formatDateToShowInSystem($model->updated_at),
                        'format' => 'html',
                    ],

                ],
            ]) ?>
        </div>
        <div class="col-md-9 col-lg-9 col-xl-9 col-sm-12 col-xs-12">
            <div id="metrics" class="kv-view-mode">
                <div class="kv-detail-view table-responsive">
                    <table id="metrics_table" class="table table-bordered table-striped detail-view">
                        <tbody>
                        <?php foreach($model->getCaseMetrics()->all() as $caseMetric){ ?>
                        <tr>
                            <th style="width: 50%; text-align: right; vertical-align: middle;"><?= $caseMetric->metric->name; ?></th>
                            <td>
                                <div class="kv-attribute"><?= $caseMetric->metricItem->name; ?></div>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
