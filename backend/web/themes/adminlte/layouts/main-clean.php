<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

backend\assets\AppAsset::register($this);

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/favicon.png'])]);
    ?>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="skin-black sidebar-mini fixed">

<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content-clean.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'footer.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>

<?php
$this->registerJsFile('@web/js/jquery.slimscroll.min.js');
$this->registerJsFile('@web/js/Chart.min.js');
$this->registerJsFile('@web/js/demo.js');
$this->registerJsFile('@web/js/customJS.js');
?>

</body>
</html>

<?php $this->endPage() ?>


