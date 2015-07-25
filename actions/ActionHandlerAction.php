<?php

namespace voskobovich\grid\advanced\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class ActionHandlerAction
 * @package voskobovich\grid\advanced\actions
 */
class ActionHandlerAction extends Action
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
    public $redirectRoute = 'index';

    /**
     * @var array
     */
    public $handlers = [
        self::WITH_SELECTED => [
            'delete' => __NAMESPACE__ . '\ActionHandlers::deleteSelected'
        ],
        self::WITH_ALL => [
            'delete' => __NAMESPACE__ . '\ActionHandlers::deleteAll'
        ]
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass == null) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $post = Yii::$app->request->post();

        if (!empty($this->handlers[$post['with']][$post['action']])) {
            $handlerName = $this->handlers[$post['with']][$post['action']];

            $params = [$this->modelClass];
            if ($post['with'] == self::WITH_SELECTED && !empty($post['selection'])) {
                $params[] = $post['selection'];
            }
            call_user_func_array($handlerName, $params);

            if ($this->redirectRoute) {
                $this->controller->redirect([$this->redirectRoute]);
            }
        }
    }
}