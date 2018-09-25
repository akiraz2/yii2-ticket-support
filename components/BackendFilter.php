<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\components;

use akiraz2\support\traits\ModuleTrait;
use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'backend' => [
 *             'class' => common\components\BackendFilter::class,
 *             'actions' => [
 *                 'index',
 *                 'view',
 *                 'create',
 *                 'update',
 *                 'delete'
 *             ],
 *         ],
 *     ];
 * }
 * ```
 */

/**
 * Class BackendFilter *
 */
class BackendFilter extends Behavior
{
    use ModuleTrait;

    public $actions = [];

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return bool
     * @throws NotFoundHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (in_array($action, $this->actions) or in_array('*', $this->actions)) {
            if (Yii::$app->id != $this->getModule()->appBackendId) {
                $event->isValid = false;
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        return $event->isValid;
    }
}