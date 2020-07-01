<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\AupRole */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Rol AUP');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Roles AUP'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aup-role-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
