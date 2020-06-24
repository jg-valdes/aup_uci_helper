<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Discipline */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Disciplina').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Disciplinas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="discipline-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
