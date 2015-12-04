<?php

namespace voskobovich\grid\advanced\columns;

use yii\base\Model;
use yii\helpers\Html;

/**
 * Class DataColumn
 * @package voskobovich\grid\advanced\columns
 */
class DataColumn extends \yii\grid\DataColumn
{
    /**
     * @var array the HTML attributes for the filter input fields. This property is used in combination with
     * the [[filter]] property. When [[filter]] is not set or is an array, this property will be used to
     * render the HTML attributes for the generated filter input fields.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $filterInputOptions = ['class' => 'form-control input-sm', 'id' => null];

    /**
     * @var array
     */
    public $filterInputWrapperOptions = ['class' => 'control-wrapper'];

    /**
     * @inheritdoc
     */
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                $content = Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . $error;
            } else {
                $content = Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . $error;
            }

            return Html::tag('div', $content, $this->filterInputWrapperOptions);
        } else {
            return parent::renderFilterCellContent();
        }
    }
}