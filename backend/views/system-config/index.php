<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\settingsSystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuración del Sistema';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Registrar Configuración', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        $url = \yii\helpers\Url::to(['delete-selected']);
        $js = <<<JS
        $(document).on("click", "#delete_btn",function(event){
        event.preventDefault();
       
        var keys = $('#systemConfigGridView').yiiGridView('getSelectedRows');
        if(keys.length > 0){
        if(confirm("¿Seguro que desea eliminar estos elementos?")){
              $.ajax({
                type: 'POST',
                url :  '$url',
                data : {selection: keys},
                success : function(data) {
                    $.pjax.reload({container:'#systemConfigGrid'});
                },
                
                
            }).fail(function(error){
                  console.log(error);
            });
        }
        }else{
        alert('Por favor debe seleccionar algún elemento!');
}
        });
       
JS;
        $this->registerJs($js); ?>
        <?= Html::a('Eliminar configuraciones', ['delete-selected'], ['id' => 'delete_btn', 'class' => 'btn btn-danger pull-right']) ?>

    </p>

    <?php Pjax::begin(['id' => 'systemConfigGrid']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'systemConfigGridView',
        'pager' => [
            'firstPageLabel' => 'Primera',
            'lastPageLabel' => 'Última',
            'prevPageCssClass' => 'prev',
            'nextPageCssClass' => 'next',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::hasRole("Admin"),
                'contentOptions' => ['style' => 'width:2%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],
            [
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width:8%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
                'visible' => Yii::$app->user->isSuperadmin
            ],

            [
                'attribute' => 'name',
                'value' => 'name',
                'contentOptions' => ['style' => 'width:5%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],

            [
                'attribute' => 'json_config',
                'value' => 'json_config',
                'contentOptions' => ['style' => 'width:10%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],

            [
                'attribute' => 'status',
                'filter' => ["1" => "Activo", "0" => "Inactivo"],
                'value' => function (\backend\models\settings\SystemConfig $model) {
                    $url = \yii\helpers\Url::to(['/system-config/status', 'id' => $model->id]);
                    return \kartik\switchinput\SwitchInput::widget([
                        'name' => 'status',
                        'value' => $model->status,
                        'pluginOptions' => [
                            'size' => 'mini',
                            'onText' => '<i class="glyphicon glyphicon-ok"></i>',
                            'offText' => '<i class="glyphicon glyphicon-remove"></i>',
                            'onColor' => 'primary',
                            'offColor' => 'danger'
                        ],
                        'pluginEvents' => [
                            "switchChange.bootstrapSwitch" => "function() { 
                                    $.ajax('$url', {
                                        type: 'POST'
                                    }).done(function(data) {
                                        $.pjax.reload({container: '#systemConfigGrid'});
                                    });
                                }",
                        ]
                    ]);
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:8%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],

            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'contentOptions' => ['style' => 'width:10%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
                'filter' => \kartik\date\DatePicker::widget([
                    'id' => 'created-date-picker',
                    'model' => $searchModel,
                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                    'attribute' => 'created_date',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd',
                        'autoclose' => true,
                    ],
                ]),
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acciones',
                'contentOptions' => ['style' => 'width:6%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
//                'visibleButtons' => [
//                    'delete' => User::hasPermission("ManageSystemConfig"),
//                    'update' => User::hasPermission("ManageSystemConfig"),
//                    'view' => User::hasPermission("VisitSystemConfig"),
//                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
