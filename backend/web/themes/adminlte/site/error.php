<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <p>
                <?= 'El error anterior ocurrió mientras el servidor web estaba procesando su solicitud. Póngase en contacto con nosotros si cree que se trata de un error del servidor. Gracias.' ?>
                <?= 'Mientras tanto, puede' ?> <a href='<?= Yii::$app-> homeUrl?> '> <?= 'Volver al inicio' ?> </a>.
            </p>

        </div>
    </div>

</section>
