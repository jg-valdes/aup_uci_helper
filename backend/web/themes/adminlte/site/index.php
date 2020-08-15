<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;

/* @var $this yii\web\View */
/* @var $tree array with all information */

$this->title = Setting::getName();

?>

    <div class="site-index">
        <div class="row">
            <div class="col-md-4 col-xl-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="treeview-animated" style="overflow: hidden scroll;">
                    <h5 class=""><?= Yii::t("backend", "Documentación") ?>
                        (<?= \yii\helpers\Html::a(Yii::t("backend", "Predecir Escenario"), ['/site/predictor']) ?>)</h5>
                    <hr>
                    <ul class="treeview-animated-list">
                        <li class="treeview-animated-items">
                            <a class="closed">
                                <i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                <span><i class="fa fa-folder-open"></i><b><?= Yii::t("backend", "Escenarios") ?></b></span>
                            </a>
                            <ul class="nested">
                                <?php foreach ($tree as $treeItem) { ?>
                                    <li class="treeview-animated-items">
                                        <a class="closed"><i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                            <span><i class="fa fa-folder-open"></i><?= $treeItem['scenario']['name']; ?></span></a>
                                        <ul class="nested">
                                            <?php if (isset($treeItem['scenario']['disciplines']) && !empty($treeItem['scenario']['disciplines'])) { ?>
                                                <?php foreach ($treeItem['scenario']['disciplines'] as $discipline): ?>
                                                    <li class="treeview-animated-items">
                                                        <a class="closed"><i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                                            <span><i class="fa fa-folder-open"></i><?= $discipline['name']; ?></span>
                                                        </a>
                                                        <ul class="nested">
                                                            <?php if (isset($discipline['processes']) && !empty($discipline['processes'])) { ?>
                                                                <?php foreach ($discipline['processes'] as $process): ?>
                                                                    <li class="treeview-animated-items">
                                                                        <a class="closed"><i class="fa fa-angle-right"
                                                                                             style="font-size: 1.5rem;"></i>
                                                                            <span><i class="fa fa-folder-open"></i><?= $process['name']; ?></span></a>
                                                                        <ul class="nested">
                                                                            <?php if (isset($process['artifacts']) && !empty($process['artifacts'])) { ?>
                                                                                <?php foreach ($process['artifacts'] as $artifact): ?>
                                                                                    <li>
                                                                                        <div class="treeview-animated-element">
                                                                                            <i class="fa fa-file-o"></i><?= $artifact['name']; ?>
                                                                                    </li>
                                                                                <?php endforeach; ?>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


<?php
$this->registerJs("$('.treeview-animated').mdbTreeview();");
?>