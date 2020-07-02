<?php

use common\widgets\DetailView;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $model \backend\models\business\AupRole */

?>
<?= DetailView::widget([
    'model' => $model,
    'labelColOptions' => ['style' => 'width: 40%'],
    'attributes' => [
        'id',
        [
            'attribute'=> 'views',
            'value'=> GlobalFunctions::getFormattedViewsCount($model->views, true),
            'format'=> 'html',
        ],
        'name',
        [
            'attribute'=> 'description',
            'value'=> $model->getDescription(),
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