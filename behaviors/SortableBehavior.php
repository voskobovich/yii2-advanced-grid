<?php

namespace voskobovich\grid\advanced\behaviors;

use himiklab\sortablegrid\SortableGridBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;


/**
 * Class SortableBehavior
 * @package voskobovich\grid\advanced\behaviors
 */
class SortableBehavior extends SortableGridBehavior
{
    /**
     * @throws InvalidConfigException
     */
    public function beforeInsert()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$model->hasAttribute($this->sortableAttribute)) {
            throw new InvalidConfigException("Invalid sortable attribute `{$this->sortableAttribute}`.");
        }

        $maxOrder = $model->find()->max($this->sortableAttribute);
        $model->{$this->sortableAttribute} = $maxOrder + 1;
    }
} 