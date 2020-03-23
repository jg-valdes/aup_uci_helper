<?php

use yii\helpers\Html;
use backend\models\auth\UserProfile;

/* @var $this yii\web\View */
/* @var $model UserProfile */

$this->title =  'Perfil';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile">


    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <img class="profile-user-img img-responsive img-circle" src="<?= $model->generateFileRoute(false); ?>" alt="User profile picture">

                        <h3 class="profile-username text-center"><?= $model->getFullName() ?></h3>
                        <div class="text-center">
                         <?= Html::a('Cambiar Contraseña',['/user-profile/change-own-password'],['class'=>'btn btn-info margin']) ?>
                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#settings" data-toggle="tab"><?= 'Mis datos' ?></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="settings">
	                        <?= $this->render('_custom_form', [
		                        'model' => $model,
	                        ]) ?>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->


</div>
