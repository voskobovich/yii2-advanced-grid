<?php

namespace voskobovich\grid\advanced\helpers;

use voskobovich\alert\helpers\AlertHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;


/**
 * Class ActionHandlers
 * @package voskobovich\grid\advanced\helpers
 */
class ActionHandlers
{
    /**
     * Deleting only selected records from DB
     * @param $model ActiveRecord
     * @param $ids array
     * @return $this
     */
    public static function deleteSelected($model, array $ids)
    {
        if (!empty($ids) && is_array($ids)) {
            /** @var ActiveRecord[] $models */
            $models = $model::findAll(['id' => $ids]);
            self::deleteModels($models);
        }

        return Yii::$app->response->redirect(['index']);
    }

    /**
     * Deleting all records from DB
     * @param $model ActiveRecord
     * @return $this
     * @throws \Exception
     */
    public static function deleteAll($model)
    {
        /** @var ActiveRecord[] $models */
        $models = $model::find()->all();
        self::deleteModels($models);

        return Yii::$app->response->redirect(['index']);
    }

    /**
     * @param $models ActiveRecord[]
     */
    public static function deleteModels($models)
    {
        $errorIds = [];

        foreach ($models as $model) {
            try {
                $model->delete();
            } catch (Exception $ex) {
                $errorIds[] = $model->getPrimaryKey(false);
            }
        }

        if (!empty($errorIds)) {
            AlertHelper::error(Yii::t(
                'vendor/voskobovich/yii2-advanced-grid/interface/common',
                'Can not delete ids: {ids}, they are in use.', [
                'ids' => implode(', ', $errorIds)
            ]));
        } else {
            AlertHelper::success(Yii::t(
                'vendor/voskobovich/yii2-advanced-grid/interface/common',
                'Successfully removed!'
            ));
        }
    }
}