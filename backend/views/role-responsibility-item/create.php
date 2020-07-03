<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\RoleResponsibilityItem */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Elemento de Responsabilidad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Elementos de Responsabilidad'), 'url' => ['index', 'id'=>$model->role_responsibility_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-responsibility-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
