<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body layadmin-themealias="default">
<?php $this->beginBody() ?>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            <?= Breadcrumbs::widget([
                'tag' => 'span',
                'homeLink' => false,
                'options' => ['class' => 'layui-breadcrumb', 'style' => 'visibility:visible;'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                /**
                 * 在面包中使用面包屑 
                 * example:
                 *  $this->params['breadcrumbs'][] = ['label'=> 'Main', 'url'=>['main']];
                 *  $this->params['breadcrumbs'][] = $this->title;
                 */  
                'itemTemplate' => "{link}<span lay-separator>/</span>", // template for all links
                'activeItemTemplate' => "<a><cite>{link}</cite></a>",

            ]) ?>
        </div>

        <div class="layui-card-body">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
