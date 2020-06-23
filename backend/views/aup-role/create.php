<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\AupRole */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Aup Role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Aup Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aup-role-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
