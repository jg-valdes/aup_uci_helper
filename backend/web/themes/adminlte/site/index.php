<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;

/* @var $this yii\web\View */

$this->title = Setting::getName();

?>

<div class="site-index">
    <div class="row">
        <div class="col-md-12 col-xl-12 col-xs-12 col-lg-12">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue-gradient">
                    <div class="inner">
                        <h3><?= GlobalFunctions::getFormattedViewsCount(User::find()->where(['status'=>User::STATUS_ACTIVE])->count()); ?></h3>

                        <p><?= Yii::t('backend','Usuarios') ?></p>
                    </div>
                    <div class="icon" style="margin-top: 6px;">
                        <i class="fa fa-users"></i>
                    </div>
                        <a href="<?= Yii::$app->urlManager->createUrl('/security/user/index'); ?>"
                           class="small-box-footer"><?= Yii::t('backend', 'Ir a Usuarios') ?> <i
                                    class="fa fa-arrow-circle-right"></i></a>

                </div>
            </div>
        </div>
    </div>
</div>
