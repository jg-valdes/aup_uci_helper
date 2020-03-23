<?php

use yii\web\View;
use backend\models\settings\Setting;

/* @var $this yii\web\View */
/* @var $last_shipping \backend\models\business\Shipping[] */

$this->title = Setting::getName();

?>


<div class="col-md-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Bienvenido</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <h3>Bienvenido <?= Yii::$app->user->username; ?></h3>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->

        <!-- /.box-footer -->
    </div>
</div>


