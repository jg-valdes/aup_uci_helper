<?php
use yii\helpers\Html;
use common\models\User;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use backend\models\settings\Setting;

/* @var $this \yii\web\View */
/* @var $content string */

$base= Url::base().'/';
$return_url = str_replace($base,'',Url::current());
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img class="logo-header-mini" src="'.Setting::getUrlLogoBySettingAndType(3).'" alt="'.Setting::getName().'"></span><span class="logo-lg"><img class="logo-header-lg" src="'.Setting::getUrlLogoBySettingAndType(2).'" alt="'.Setting::getName().'"></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?= \backend\models\auth\UserProfile::renderCurrentAvatar(['class' => 'user-image']); ?>
                        <span class="hidden-xs"><?= Yii::$app->user->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?= \backend\models\auth\UserProfile::renderCurrentAvatar([
                                    'class' => 'img-circle',
                                'alt'=>"User Avatar"
                            ]); ?>


                            <p>
	                            <?= Yii::$app->user->username ?>
                                <small></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
	                            <?= Html::a(
		                            'Perfil',
		                            ['/user-profile/profile', 'id'=>Yii::$app->user->id],
		                            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
	                            ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Cerrar sesión',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>


