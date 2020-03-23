<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use webvimark\modules\UserManagement\models\User;
use backend\models\auth\UserProfile;

/* @var $this yii\web\View */
/* @var $model \backend\models\auth\UserProfile */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Perfiles de usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$status = "";
if (User::hasPermission("ManageUserProfiles") && !$model->user->superadmin) {
    $url = \yii\helpers\Url::to(['/user-profile/status', 'id' => $model->id]);
    $status = \kartik\switchinput\SwitchInput::widget([
        'name' => 'status',
        'value' => $model->user->status,
        'pluginOptions' => [
            'size' => 'mini',
            'onText' => 'Activo',
            'offText' => 'Inactivo',
            'onColor' => 'primary',
            'offColor' => 'danger'
        ],
        'pluginEvents' => [
            "switchChange.bootstrapSwitch" => "function() {
                      $.ajax('$url', {
                        type: 'POST'
                      }).done(function(data) {
                          console.log(data);
                      });
                  }",
        ]
    ]);
} else {
    $status = Html::tag("span", $model->isActive() ? "Activo" : "Inactivo", [
        'class' => $model->isActive() ? 'label label-primary' : 'label label-danger']);
}

?>
<div class="user-profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (User::hasPermission("ManageUserProfiles")) { ?>
            <?= Html::a('Actualizar', ['update', 'id' => $model->id, 'isFromIndex' => false], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Crear', ['create', 'isFromIndex' => false], ['class' => 'btn btn-primary']) ?>

            <?php if (Yii::$app->user->isSuperadmin) { ?>
                <?= \webvimark\modules\UserManagement\components\GhostHtml::a(
                    'Roles y permisos',
                    ['/user-management/user-permission/set', 'id' => $model->user_id],
                    ['class' => 'btn btn-info']
                ) ?>

            <?php } ?>
            <?php if (User::hasRole("Admin")) { ?>
                <?= Html::a('Cambiar contraseña', ['change-password', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?php } ?>
            <?= Html::a('Listar usuarios', ['index'], ['class' => 'btn btn-default']); ?>

            <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger pull-right',
                'data' => [
                    'confirm' => 'Seguro desea eliminar este elemento?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'value' => $model->id,
                'visible' => Yii::$app->user->isSuperadmin
            ],
            [
                'attribute' => 'roles',
                'value' => $model->getFormattedRoles(),
                'format' => 'raw',
                'visible' => User::hasRole("Admin")
            ],
            [
                'attribute' => 'avatar',
                'value' => UserProfile::renderAvatar($model->user_id, ['style' => 'width:150px']),
                'format' => 'raw'
            ],
            [
                'attribute' => 'user_id',
                'value' => $model->getIDLinkForUserParent(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'first_name',
                'value' => $model->getFullName(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'email',
                'value' => $model->getEmail(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'gender',
                'value' => $model->getGender(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'phone_mobile',
                'value' => $model->getMobile(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'phone_fixed',
                'value' => $model->getPhone(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'address',
                'value' => $model->getAddress(),
                'format' => 'raw'
            ],
            'created_at',
            'updated_at',
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
                'format' => 'raw'
            ],
            [
                'attribute' => 'coupons',
                'value' => \backend\models\business\UserCoupon::generateCouponLinksForUser($model->user_id),
                'format' => 'raw'
            ],
        ],
    ]) ?>

</div>
