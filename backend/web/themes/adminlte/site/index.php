<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;

/* @var $this yii\web\View */

$this->title = Setting::getName();

?>

<div class="site-index">
    <div class="row">
        <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12 col-xs-12">
            <div class="treeview-animated">
                <h5 class=""><?= Yii::t("backend", "Documentación") ?></h5>
                <hr>
                <ul class="treeview-animated-list">
                    <li class="treeview-animated-items">
                        <a class="closed">
                            <i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                            <span><i class="fa fa-folder-open"></i>Folder</span>
                        </a>
                        <ul class="nested">
                            <li>
                                <div class="treeview-animated-element"><i class="fa fa-file-o"></i>Item
                            </li>
                            <li class="treeview-animated-items">
                                <a class="closed"><i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                    <span><i class="fa fa-folder-open"></i>Subfolder</span></a>
                                <ul class="nested">
                                    <li>
                                        <div class="treeview-animated-element"><i class="fa fa-users"></i>Subitem
                                    </li>
                                    <li>
                                        <div class="treeview-animated-element"><i class="fa fa-file-o"></i>File
                                    </li>
                                </ul>
                            </li>
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