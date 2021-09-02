<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\models\Parse;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'List files', 'url' => [Url::toRoute('/list-file/index')]],
            ['label' => 'Add file', 'url' => [Url::toRoute('/list-file/add')]],
            [
                'label' => 'Parsed DB',
                'items' => [
                    ['label' => 'xml', 'url' => Url::toRoute(['/parse/index', 'format' => Parse::FORMAT_XML])],
                    ['label' => 'txt', 'url' => Url::toRoute(['/parse/index', 'format' => Parse::FORMAT_TXT])],
                    ['label' => 'csv', 'url' => Url::toRoute(['/parse/index', 'format' => Parse::FORMAT_CSV])],
                ],
            ],
            [
                'label' => 'Join parsed files',
                'items' => [
                    ['label' => 'xml', 'url' => Url::toRoute(['/parse/join', 'format' => Parse::FORMAT_XML])],
                    ['label' => 'txt', 'url' => Url::toRoute(['/parse/join', 'format' => Parse::FORMAT_TXT])],
                    ['label' => 'csv', 'url' => Url::toRoute(['/parse/join', 'format' => Parse::FORMAT_CSV])],
                ],
            ],
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
