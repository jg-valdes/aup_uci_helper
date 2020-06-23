<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\AupRole */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Aup Role').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Aup Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="aup-role-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
