<?php
use yii\widgets\Breadcrumbs;
use common\widgets\Custom_Alert;

?>
<div class="content-wrapper">
    <section class="content-header">

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <br>

    <section class="content">
        <?= Custom_Alert::widget()  ?>

        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
	                    <?php if (isset($this->blocks['content-header'])) { ?>
                             <h3 class="box-title"><?= $this->blocks['content-header'] ?></h3>
	                    <?php } else { ?>
                            <h3 class="box-title">
			                    <?php
			                    if ($this->title !== null) {
				                    echo \yii\helpers\Html::encode($this->title);
			                    } else {
				                    echo \yii\helpers\Inflector::camel2words(
					                    \yii\helpers\Inflector::id2camel($this->context->module->id)
				                    );
				                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
			                    } ?>
                            </h3>
	                    <?php } ?>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
	                    <?= $content ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


    </section>
</div>


