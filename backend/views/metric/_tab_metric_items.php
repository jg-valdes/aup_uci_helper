<?php

use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $model \backend\models\knn\Metric */
/* @var $modelItem \backend\models\knn\MetricItem */
/* @var $modelRelation \backend\models\knn\MetricMetricItem */
/* @var $metricItemsDataProvider \yii\data\ActiveDataProvider */

$create_button = Html::button('<i class="fa fa-plus"></i> ' . Yii::t('backend', 'Crear'), [
    'data-toggle' => 'modal',
    'data-target' => '#metricItemModal',
    'class' => 'btn btn-success btn-flat margin',
    'title' => Yii::t('backend', 'Crear') . ' ' . Yii::t('backend', 'Opción')]);

$custom_template_action_column = ['delete'];
$custom_buttons_action_column = [
    'delete' => function ($url, $model) {
        $url_action = Url::to(['/metric/delete-item', 'id' => $model->id]);
        $options = [
            'class' => 'btn btn-xs btn-danger btn-flat',
            'title' => Yii::t('yii', 'Delete'),
            'data-toggle' => 'tooltip',
            'data-method' => 'post',
            'data-confirm' => Yii::t('backend', '¿Seguro desea eliminar este elemento?'),
        ];
        return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url_action, $options);
    },
];


$toggle_data_options = [];

$panel = [
    'type' => 'default',
    'after' => false,
    'before' => '',
];
$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button, $metricItemsDataProvider, $custom_template_action_column, $custom_buttons_action_column);

$custom_elements_gridview->toolbar = [['content' => $create_button]];
$custom_elements_gridview->setPanel($panel);
$custom_elements_gridview->togle_data_options = $toggle_data_options;


?>

    <div class="box box-solid">

        <!-- /.box-header -->
        <div class="box-body">

            <div class="col-sm-12 col-md-12 col-xl-12">
                <?php \yii\widgets\Pjax::begin(['id' => 'items-pjax']); ?>
                <?= GridView::widget([
                    'id' => 'gridMetricItems',
                    'dataProvider' => $metricItemsDataProvider,
                    'responsiveWrap' => false,
                    'hover' => true,
                    'pager' => [
                        'firstPageLabel' => Yii::t('backend', 'Primero'),
                        'lastPageLabel' => Yii::t('backend', 'Último'),
                    ],
                    'hover' => true,
                    'persistResize' => true,
                    'columns' => [
                        [
                            'attribute' => 'metric_item_id',
                            'value' => function ($data) {
                                return $data->getMetricItemName();
                            },
                            'class' => 'kartik\grid\EditableColumn',
                            'editableOptions' => function ($model, $key, $index) {
                                return [
                                    'size' => 'md',
                                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                    'options' => ["value" => $model->getMetricItemName()],
                                    'formOptions' => [
                                        'action' => ['/metric/update-item']
                                    ],

                                ];

                            },

                            'headerOptions' => ['class' => 'custom_width'],
                            'contentOptions' => ['class' => 'custom_width'],
                        ],

                        [
                            'attribute' => 'weight',
                            'class' => 'kartik\grid\EditableColumn',
                            'editableOptions' => function ($model, $key, $index) {
                                return [
                                    'size' => 'md',
                                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                    'formOptions' => [
                                        'action' => ['/metric/update-item']
                                    ],
                                ];
                            },
                            'headerOptions' => ['class' => 'custom_width'],
                            'contentOptions' => ['class' => 'custom_width'],
                        ],

                        [
                            'attribute' => 'status',
                            'value' => function ($data) {
                                return GlobalFunctions::getStatusValue($data->status);
                            },
                            'format' => 'html',
                            'refreshGrid' => true,
                            'class' => 'kartik\grid\EditableColumn',
                            'editableOptions' => function ($model, $key, $index) {
                                return [
                                    'inputType' => Editable::INPUT_SWITCH,
                                    'size' => 'sm',
                                    'options' => ['class' => 'form-control'],
                                    'pluginOptions' => [
                                        "onText" => Yii::t("backend", "Activo"),
                                        "offText" => Yii::t("backend", "Inactivo")
                                    ],
                                    'formOptions' => [
                                        'action' => ['/metric/update-item']
                                    ],
                                ];
                            },
                            'headerOptions' => ['class' => 'custom_width'],
                            'contentOptions' => ['class' => 'custom_width'],
                        ],

                        [
                            'attribute' => 'created_at',
                            'value' => function ($data) {
                                return GlobalFunctions::formatDateToShowInSystem($data->created_at);
                            },
                            'contentOptions' => ['class' => 'kv-align-left kv-align-middle'],
                            'hAlign' => 'center',
                        ],
                        $custom_elements_gridview->getActionColumn(),

                    ],
                    'toolbar' => $custom_elements_gridview->getToolbar(),

                    'panel' => $custom_elements_gridview->getPanel(),

                    'toggleDataOptions' => []
                ]); ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="modal fade" id="metricItemModal" tabindex="-1" role="dialog" aria-labelledby="ModalMetricItem">
        <div class="modal-dialog" role="document" style="width: 70% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?= Yii::t("backend", "Cerrar"); ?>"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="metricItemModalLabel"><?= Yii::t("backend", "Agregar Opción"); ?></h4>
                </div>
                <?php $form = \kartik\form\ActiveForm::begin([
                    'id' => 'metric-item-form',
                    'action' => ['/metric/create-item-ajax', 'id' => $model->id]
                ]); ?>
                <div class="modal-body">
                    <?= $this->render("_form_metric_item", ['item' => $modelItem, 'relation' => $modelRelation, 'form' => $form]); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= Yii::t("backend", "Cancelar"); ?></button>
                    <button id="image-ajax-submit" type="submit" class="btn btn-primary"><i
                                class="fa fa-plus"></i><?= Yii::t('backend', 'Agregar'); ?></button>
                </div>
                <?php \kartik\form\ActiveForm::end(); ?>
            </div>
        </div>
    </div>

<?php

$js = <<<JS
    $(function(){
        
        function assignFormTrigger(){
            $("#metric-item-form").on("beforeSubmit", function(event) {
            event.preventDefault(); // stopping submitting
        
            let form = this;
            let url = $(form).attr('action');
            let formdata = new FormData(form);
    
            $.ajax({
                url: url,
                type: 'post',
                data: formdata,
                processData: false,
                contentType: false,
            })
            .done(function(response) {
                if (response.data.success) {
                    $("#metricItemModal").modal('hide');
                    $.pjax.reload({container: '#gridMetricItems', timeout: 2000});
                    $(form).trigger('reset');
                    assignFormTrigger();
                }else{
                    if(response.data.hasOwnProperty('errors'))
                    $.each(response.data.errors, function(key, val) {
                        $("#metric-metric-item-"+key).after("<div class=\"help-block\">"+val+"</div>");
                        $("#metric-metric-item-"+key).closest(".form-group").addClass("has-error");
                        });
                }
            })
            .fail(function(e) {
                console.log("No conection to server");
                console.log(e);
                $("#metricItemModal").modal('hide');
                assignFormTrigger();
            });
        
        }).on('submit', function(e){
            e.preventDefault();
        });     
        }
        
        assignFormTrigger();
        
    }); 
   
JS;

$this->registerJs($js);
?>