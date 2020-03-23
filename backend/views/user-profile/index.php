<?php

use yii\helpers\Html;
use yii\grid\GridView;
use webvimark\modules\UserManagement\models\User;
use backend\models\auth\UserProfile;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\UserProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perfiles de usuario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear perfil de usuario', ['create'], ['class' => 'btn btn-primary']) ?>
        <?php
        $url = \yii\helpers\Url::to(['delete-selected']);
        $js = <<<JS
        $(document).on("click", "#delete_btn",function(event){
        event.preventDefault();
       
        var keys = $('#profilesGridView').yiiGridView('getSelectedRows');
        if(keys.length > 0){
        if(confirm("¿Seguro que desea eliminar estos elementos?")){
              $.ajax({
                type: 'POST',
                url :  '$url',
                data : {selection: keys},
                success : function(data) {
                    $.pjax.reload({container:'#profilesGrid'});
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
        <?= Html::a('Eliminar perfiles', ['delete-selected'], ['id' => 'delete_btn', 'class' => 'btn btn-danger pull-right']) ?>

    </p>

    <?php \yii\widgets\Pjax::begin(['id' => 'profilesGrid']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'profilesGridView',
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
                'visible' => Yii::$app->user->isSuperadmin,
                'contentOptions' => ['style' => 'width:5%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],
            [
                'attribute' => 'user_id',
                'value' => function (UserProfile $model) {
                    return $model->getIDLinkForUserParent();
                },
                'filter' => \kartik\select2\Select2::widget([
                    'name' => 'user_id',
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => UserProfile::getSelectMap(),
                    'language' => 'es',
                    'options' => [
                        'placeholder' => 'Usuario',
                        'multiple' => false
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:8%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ]
            ],
            [
                'attribute' => 'first_name',
                'value' => function (UserProfile $model) {
                    return $model->getFullName();
                },
                'format' => "raw",
                'contentOptions' => ['style' => 'width:10%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],
            [
                'attribute' => 'email',
                'value' => function (UserProfile $model) {
                    return $model->getEmail();
                },
                'format' => "raw",
                'contentOptions' => ['style' => 'width:10%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],
            [
                'attribute' => 'status',
                'filter' => ["1" => "Activo", "0" => "Inactivo"],
                'value' => function (UserProfile $model) {
                    if (User::hasRole("Admin")) {
                        $url = \yii\helpers\Url::to(['/user-profile/status', 'id' => $model->id]);
                        return \kartik\switchinput\SwitchInput::widget([
                            'name' => 'status',
                            'value' => $model->user->status,
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
                                            console.log(data);
                                            //$.pjax.reload({container: '#doctorGrid'});
                                        });
                                    }",
                            ]
                        ]);
                    } else {
                        return Html::tag("span", $model->isActive() ? "Activo" : "Inactivo", [
                            'class' => $model->isActive() ? 'label label-primary' : 'label label-danger']);
                    }
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:8%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acciones',

                'contentOptions' => ['style' => 'width:6%; text-align:center'],
                'headerOptions' => [
                    'style' => 'text-align:center',
                ],
                'visibleButtons' => [
                    'delete' => function (UserProfile  $model) {
                        return !$model->user->superadmin || $model->user_id !== Yii::$app->user->id;
                    }
                ],
                'visible' => User::hasRole("Admin")
            ],
        ],
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>
</div>
