<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create Role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['roles']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_roleform', [
    'model' => $model,
]) ?>
