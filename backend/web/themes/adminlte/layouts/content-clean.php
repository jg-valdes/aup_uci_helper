<?php
use yii\widgets\Breadcrumbs;
use common\widgets\Custom_Alert;

?>
<div class="content-wrapper">
    <section class="content-header">

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <br>

    <section class="content">
        <?= Custom_Alert::widget()  ?>

        <div class="row">
            <?= $content ?>
        </div>
        <!-- /.row -->


    </section>
</div>


