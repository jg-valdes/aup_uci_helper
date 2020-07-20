<?php

use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $model \backend\models\business\RoleResponsibility */
/* @var $modelResponsibility \backend\models\business\RoleResponsibility */
/* @var $responsibilityDataProvider \yii\data\ActiveDataProvider */

$create_button = Html::button('<i class="fa fa-plus"></i> ' . Yii::t('backend', 'Crear'), [
    'data-toggle' => 'modal',
    'data-target' => '#responsibilityModal',
    'class' => 'btn btn-success btn-flat margin',
    'title' => Yii::t('backend', 'Crear') . ' ' . Yii::t('backend', 'Responsabilidad')]);

$custom_template_action_column = ['items', 'delete'];
$custom_buttons_action_column = [
    'items' => function ($url, $model) {
        $url_action = Url::to(['/role-responsibility-item/index', 'id' => $model->id]);
        $options = [
            'class' => 'btn btn-xs btn-default btn-flat',
            'title' => Yii::t('backend', 'Elementos'),
            'data-toggle' => 'tooltip',
        ];
        return Html::a('<i class="glyphicon glyphicon-list-alt"></i>', $url_action, $options);
    },
    'delete' => function ($url, $model) {
        $url_action = Url::to(['/aup-role/delete-responsibility', 'id' => $model->id]);
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
$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button, $responsibilityDataProvider, $custom_template_action_column, $custom_buttons_action_column);

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
                    'id' => 'gridRoleResponsibilities',
                    'dataProvider' => $responsibilityDataProvider,
                    'responsiveWrap' => false,
                    'hover' => true,
                    'pager' => [
                        'firstPageLabel' => Yii::t('backend', 'Primero'),
                        'lastPageLabel' => Yii::t('backend', 'Último'),
                    ],
                    'hover' => true,
                    'persistResize' => true,
                    'columns' => [
                        $custom_elements_gridview->getSerialColumn(),
                        [
                            'attribute' => 'name',
                            'value' => function ($data) {
                                return $data->name;
                            },
                            'class' => 'kartik\grid\EditableColumn',
                            'editableOptions' => function ($model, $key, $index) {
                                return [
                                    'size' => 'md',
                                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                    'pjaxContainerId' => 'items-pjax',
                                    'formOptions' => [
                                        'action' => ['/aup-role/update-responsibility']
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
                                    'pjaxContainerId' => 'items-pjax',
                                    'size' => 'sm',
                                    'options' => [
                                        'class' => 'form-control',
                                        'pluginOptions' => [
                                            "onText" => Yii::t("backend", "Activo"),
                                            "offText" => Yii::t("backend", "Inactivo")
                                        ],
                                    ],
                                    'formOptions' => [
                                        'action' => ['/aup-role/update-responsibility']
                                    ],
                                ];
                            },
                            'headerOptions' => ['class' => 'custom_width'],
                            'contentOptions' => ['class' => 'custom_width'],
                            'hAlign' => 'center',
                            'vAlign' => 'center',
                        ],

                        [
                            'attribute' => 'created_at',
                            'value' => function ($data) {
                                return GlobalFunctions::formatDateToShowInSystem($data->created_at);
                            },
                            'contentOptions' => ['class' => 'kv-align-middle'],
                            'hAlign' => 'center',
                            'vAlign' => 'center',
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

    <div class="modal fade" id="responsibilityModal" tabindex="-1" role="dialog" aria-labelledby="ModalRoleResponsibility">
        <div class="modal-dialog" role="document" style="width: 70% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?= Yii::t("backend", "Cerrar"); ?>"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="metricItemModalLabel"><?= Yii::t("backend", "Agregar Responsabilidad"); ?></h4>
                </div>
                <?php $form = \kartik\form\ActiveForm::begin([
                    'id' => 'role-responsibility-form',
                    'action' => ['/aup-role/create-responsibility-ajax', 'id' => $model->id]
                ]); ?>
                <div class="modal-body">
                    <?= $this->render("_form_responsibility", ['model' => $modelResponsibility, 'form' => $form]); ?>
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
            $("#role-responsibility-form").on("beforeSubmit", function(event) {
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
                    $("#responsibilityModal").modal('hide');
                    $.pjax.reload({container: '#items-pjax', timeout: 2000});
                    $(form).trigger('reset');                    
                }else{
                    if(response.data.hasOwnProperty('errors'))
                    $.each(response.data.errors, function(key, val) {
                        $("#role-responsibility-"+key).after("<div class=\"help-block\">"+val+"</div>");
                        $("#role-responsibility-"+key).closest(".form-group").addClass("has-error");
                        });
                }
            })
            .fail(function(e) {
                console.log("No connection to server");
                console.log(e);
                $("#responsibilityModal").modal('hide');
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