<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'layuiadmin/style/admin.css'
    ];
    public $js = [
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        'backend\assets\LayuiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
