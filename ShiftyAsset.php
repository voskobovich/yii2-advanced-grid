<?php

namespace voskobvich\grid\advanced;

use yii\web\AssetBundle;

/**
 * Class ShiftyAsset
 * @package voskobvich\grid\advanced
 */
class ShiftyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/voskobovich/yii2-advanced-grid/assets';
    public $js = [
        'js/shifty.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
