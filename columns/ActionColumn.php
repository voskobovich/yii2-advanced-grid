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
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-xs'
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-xs'
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'Update'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'class' => 'btn btn-default btn-xs'
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'Delete'), $url, $options);
            };
        }
    }
}