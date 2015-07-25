<?php


namespace app\behaviors;

use himiklab\sortablegrid\SortableGridBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;


/**
 * Class SortableBehavior
 * @package app\behaviors
 */
class SortableBehavior extends SortableGridBehavior
{
    /**
     * Перед сохранением записи в базу
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