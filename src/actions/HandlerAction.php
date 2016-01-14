<?php

namespace voskobovich\grid\advanced\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;


/**
 * Class HandlerAction
 * @package voskobovich\grid\advanced\actions
 */
class HandlerAction extends Action
{
    const WITH_SELECTED = 'selected';
    const WITH_ALL = 'all';

    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectUrl = ['index'];

    /**
     * @var array
     */
    public $handlers = [
        self::WITH_SELECTED => [
            'delete' => ['voskobovich\grid\advanced\helpers\ActionHandlers', 'deleteSelected']
        ],
        self::WITH_ALL => [
            'delete' => ['voskobovich\grid\advanced\helpers\ActionHandlers', 'deleteAll']
        ]
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass == null) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $post = Yii::$app->request->post();

        if (!empty($this->handlers[$post['with']][$post['action']])) {
            $params = [$this->modelClass];
            if ($post['with'] == self::WITH_SELECTED && !empty($post['selection'])) {
                $params[] = $post['selection'];
            }

            $handlerName = $this->handlers[$post['with']][$post['action']];
            call_user_func_array($handlerName, $params);

            $this->controller->redirect($this->redirectUrl);
            return null;
        }

        throw new BadRequestHttpException();
    }
}