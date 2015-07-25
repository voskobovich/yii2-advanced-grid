<?php

namespace voskobovich\grid\advanced\plugins\shifty;

use yii\web\AssetBundle;

/**
 * Class ShiftyAsset
 * @package voskobovich\grid\advanced\plugins\shifty
 */
class ShiftyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/voskobovich/yii2-advanced-grid/plugins/shifty/assets';
    public $js = [
        'js/shifty.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
