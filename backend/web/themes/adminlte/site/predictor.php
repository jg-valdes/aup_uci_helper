<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;
use yii\widgets\ActiveForm;
use kartik\widgets\RangeInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $metrics \backend\models\knn\Metric[] */
/* @var $knn array with scenarios and results */
/* @var $k_delimiter int for define KNN clusters */

$this->title = Yii::t("backend", "Selecciona las condiciones de tu proyecto");
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
            <?php $form = ActiveForm::begin(['options' => ['id' => 'quiz-form', 'class' => 'hidden']]); ?>

            <?php ActiveForm::end(); ?>
            <?php }else{ ?>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4><?= \backend\models\business\Scenario::findOne(['id'=> $knn[0]['scenario_id']])->name;?></h4>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?= Url::to(['/scenario/view', 'id'=>$knn[0]['scenario_id']]); ?>" class="small-box-footer">
                            <?= Yii::t("backend", "Ver documentación") ?>
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
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