<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use backend\models\settings\Setting;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model \backend\models\settings\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="nav-tabs-custom" style="cursor: move;">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right ui-sortable-handle">
                    <li class=""><a href="#logos" data-toggle="tab" aria-expanded="false">Logotipos</a></li>
                    <li class="active"><a href="#system" data-toggle="tab" aria-expanded="true">Nosotros</a></li>
                    <li class="pull-left header"><i class="fa fa-cogs"></i> Configuraciones</li>
                </ul>

            </div>
            <div class="box-body">
                <div class="tab-content no-padding">
                    <div class="tab-pane active" id="system">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                            </div>


                        </div>

                        <?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

                        <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                            'options' => ['rows' => 6],
                            'preset' => 'basic',
                            'clientOptions' => [
                                'filebrowserUploadUrl' => \yii\helpers\Url::to(['/site/ckeditorupload']),
                            ]
                        ]) ?>

                    </div>
                    <div class="tab-pane" id="logos">
                        <?php

                        if ($model->isNewRecord) {
                            $url_main_logo = Setting::getUrlLogoBySettingAndType(1);
                            $url_header_logo = Setting::getUrlLogoBySettingAndType(2);
                            $url_mini_header_logo = Setting::getUrlLogoBySettingAndType(3);
                        } else {
                            $url_main_logo = Setting::getUrlLogoBySettingAndType(1, $model->id);
                            $url_header_logo = Setting::getUrlLogoBySettingAndType(2, $model->id);
                            $url_mini_header_logo = Setting::getUrlLogoBySettingAndType(3, $model->id);
                        }

                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'file_main_logo')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'browseIcon' => '<i class="fa fa-camera"></i> ',
                                        'browseLabel' => 'Cambiar',
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
                                        'defaultPreviewContent' => '<img src="' . $url_main_logo . '" class="previewAvatar">',
                                        'showUpload' => false,
                                        'layoutTemplates' => [
                                            'main1' => '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                                        ],
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'file_header_logo')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'browseIcon' => '<i class="fa fa-camera"></i> ',
                                        'browseLabel' => 'Cambiar',
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
                                        'defaultPreviewContent' => '<img src="' . $url_header_logo . '" class="previewAvatar">',
                                        'showUpload' => false,
                                        'layoutTemplates' => [
                                            'main1' => '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                                        ],
                                    ]
                                ]);
                                ?>
                            </div>


                            <div class="col-sm-6">
                                <?= $form->field($model, 'file_mini_header_logo')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'browseIcon' => '<i class="fa fa-camera"></i> ',
                                        'browseLabel' => 'Cambiar',
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
                                        'defaultPreviewContent' => '<img src="' . $url_mini_header_logo . '" class="previewAvatar">',
                                        'showUpload' => false,
                                        'layoutTemplates' => [
                                            'main1' => '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                                        ],
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> Crear' : '<i class="fa fa-pencil"></i> ' . Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>

            </div>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

