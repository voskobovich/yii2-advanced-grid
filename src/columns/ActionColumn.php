<?php

namespace voskobovich\grid\advanced\columns;

use Yii;
use yii\helpers\Html;

/**
 * Class ActionColumn
 * @package voskobovich\grid\advanced\columns
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'View'),
                    'aria-label' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-xs'
                ], $this->buttonOptions);
                return Html::a(
                    Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'View'),
                    $url,
                    $options
                );
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Update'),
                    'aria-label' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-xs'
                ], $this->buttonOptions);
                return Html::a(
                    Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Update'),
                    $url,
                    $options
                );
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Delete'),
                    'aria-label' => Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Delete'),
                    'data-confirm' => Yii::t(
                        'vendor/voskobovich/yii2-advanced-grid/interface/common',
                        'Are you sure you want to delete this item?'
                    ),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-xs'
                ], $this->buttonOptions);
                return Html::a(
                    Yii::t('vendor/voskobovich/yii2-advanced-grid/interface/common', 'Delete'),
                    $url,
                    $options
                );
            };
        }
    }
}