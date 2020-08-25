<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use backend\models\business\Scenario;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $metrics \backend\models\knn\Metric[] */
/* @var $knn array with scenarios and results */
/* @var $k_delimiter int for define KNN clusters */
/* @var $model \backend\models\knn\IaCase */

if(empty($knn)){

    $this->title = Yii::t("backend", "Seleccione las condiciones de su proyecto");
}else{
    $this->title = Yii::t("backend", "Análisis completado");
}

$this->registerCssFile("@web/plugins/custom.quiz/custom.quiz.css");

?>

<div class="site-index">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
            <?php if(empty($knn)){ ?>
            <div class="quiz-container">
                <div id="quiz"></div>
            </div>
            <div class="quiz-actions">
                <button id="previous" class="btn btn-info"><?= Yii::t("backend", "Anterior") ?></button>
                <button id="next" class="btn btn-primary"><?= Yii::t("backend", "Siguiente") ?></button>
                <button id="submit_quiz" class="btn btn-success"><?= Yii::t("backend", "Predecir Escenario") ?></button>
                <div id="results"></div>
            </div>
            <?php ActiveForm::begin(['options' => ['id' => 'quiz-form', 'class' => 'hidden']]);
                  ActiveForm::end();
            }else{ ?>
                    <h5><?= Yii::t("backend", "El sistema recomienda el siguiente escenario"); ?>: <?= $model->scenario0->name;?> <a href="<?= Url::to(['/site/index']); ?>" class="small-box-footer">
                            (<?= Yii::t("backend", "Ver documentación") ?>)
                            </a>.</h5>

                <?php $form = ActiveForm::begin(['action'=> Url::to(['/ia-case/update', 'id'=>$model->id]), 'options' => ['id' => 'case-form']]); ?>
                <h6><?= Yii::t("backend", "Puede mejorar nuestra base de conocimientos seleccionando otro escenario a continuación: "); ?></h6>
                <div class="col-md-3 col-lg-3 col-xs-3 col-sm-3">
                    <?=
                    $form->field($model, "scenario_id")->widget(Select2::classname(), [
                        "data" => Scenario::getSelectMap(),
                        "options" => ["multiple"=>false],
                        "pluginOptions" => [
                            "allowClear" => false
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-2 col-lg-2 col-xs-2 col-sm-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <?= Html::submitButton('<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat form-control']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            <?php } ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
let quizOptions = [];
let tempAnswers = {};
JS;

foreach ($metrics as $metric){
    if($metric->hasMetricItems()){
        $js .= <<<JS
    tempAnswers = {};
JS;
        foreach ($metric->getMetricMetricItems()->all() as $item){
            $js .= <<<JS
                tempAnswers["{$item->metric_item_id}"] = "{$item->metricItem->name}";
JS;    
    }

    $js .= <<<JS
    quizOptions.push({
        question: "{$metric->name}",
        questionId: "{$metric->id}",
        answers: tempAnswers
    });
JS;
        }
}

$js .= <<<JS

aupUciQuiz.init(quizOptions);

JS;

$this->registerJsFile("@web/plugins/custom.quiz/custom.quiz.js");
$this->registerJs($js);

?>