<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model \backend\models\business\Artifact */

$json = $model->getModelAsJson();

?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="artifact_name"><?= $model->name ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="">
            <strong><i class="fa fa-file-text-o margin-r-5"></i> <?= Yii::t("backend", "Descripción"); ?></strong>

            <?php if($json['has_resource']) {?>
                <a style="float:right;" id="artifact_downloads_link" href="<?= $json['resource'] ?>" title="<?= Yii::t("backend", "Descargar");?>" data-toggle="tooltip" data-original-title="<?= Yii::t("backend", "Descargar");?>"> <?= Yii::t("backend", "Descargar recurso");?></a>
                <strong style="float:right; margin-right: 2%;"><i class="fa fa-cloud-download"></i> <span id="artifact_downloads"><?= $json['downloads'];?></span></strong>
            <?php } ?>

            <strong style="float:right; margin-right: 2%;"><i class="fa fa-eye"></i> <span id="artifact_views"><?= $json['views'];?></span></strong>

        </div>

        <div id="artifact_description" style="padding-top: 2%;">
            <?= $model->getDescription(); ?>
        </div>

        <hr>

        <strong><i class="fa fa-users margin-r-5"></i> <?= Yii::t("backend", "Roles"); ?></strong>
        <ul style="list-style: none">
            <?php foreach($json['roles'] as $rol):?>
                <li class="" style="margin: 1% 0px;">
                    <i class="fa fa-user"></i> <?= $rol['name'] ?>
                    <br>
                    <strong><?= Yii::t("backend", "Responsabilidades");?>:</strong>
                </li>
                <li>
                    <div class="just-padding">

                        <div class="list-group list-group-root well">
                            <?php foreach($rol['responsibilities'] as $responsibility):?>
                                <a href="#item-<?= $responsibility['id']; ?>" class="list-group-item" data-toggle="collapse">
                                    <i class="glyphicon glyphicon-chevron-right"></i><?= $responsibility['name'] ?>
                                </a>
                                <div class="list-group collapse" id="item-<?= $responsibility['id']; ?>">
                                    <?php foreach($responsibility['items'] as $item):?>
                                        <a href="#item-<?= $responsibility['id'] . "-" . $item['id']; ?>" class="list-group-item" data-toggle="collapse">
                                            <i class="glyphicon glyphicon-chevron-right"></i><?= $item['name']; ?>
                                        </a>
                                        <div class="list-group collapse" id="item-<?= $responsibility['id'] . "-" . $item['id']; ?>">
                                            <div class="row" style="padding: 10px 30px;">
                                            <div class="col-md-12 col-xl-12 col-sm-12 col-lg-12">
                                                <?php if($item['has_resource']){ ?>
                                                    <strong>Descargas:</strong> <?= $item['downloads']; ?> <a href="<?= $item['resource']; ?>" data-toggle="tooltip" title="<?= Yii::t("backend", "Descargar");?>" data-original-title="<?= Yii::t("backend", "Descargar");?>"><i class="fa fa-cloud-download"></i> </a>
                                                <?php } ?>
                                            </div>
                                                <div class="col-md-12 col-xl-12 col-sm-12 col-lg-12">
                                                    <strong>Descripción:</strong>
                                                    <br>
                                                    <?= $item['description']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </li>

            <?php endforeach; ?>
        </ul>


    </div>
    <!-- /.box-body -->
</div>

