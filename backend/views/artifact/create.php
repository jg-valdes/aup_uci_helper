<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Artifact');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Artifacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artifact-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
