<?php

namespace voskobovich\grid\advanced;

use yii\web\AssetBundle;

/**
 * Class GridViewAsset
 * @package voskobovich\grid\advanced
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/voskobovich/yii2-advanced-grid/src/assets';

    /**
     * @var array
     */
    public $css = [
        'css/grid.css'
    ];
}
