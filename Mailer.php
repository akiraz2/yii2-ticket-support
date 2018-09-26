<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support;

use PhpImap\Mailbox;
use Yii;
use yii\base\Component;

/**
 * Mailer.
 */
class Mailer extends Component
{
    /** @var string */
    public $viewPath = '@vendor/akiraz2/yii2-ticket-support/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string|array Default: `Yii::$app->params['adminEmail']` */
    public $toEmail;

    /** @var \yii\mail\BaseMailer Default: `Yii::$app->mailer` */
    public $mailerComponent;

    /** @var \akiraz2\support\Module */
    protected $module;

    protected $_mailer;

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('support');
        $this->_mailer = $this->mailerComponent === null ? Yii::$app->mailer : Yii::$app->get($this->mailerComponent);
        parent::init();
    }

    public function sendMessageToSupportEmail($subject, $view, $params = [])
    {
        if ($this->toEmail === null) {
            $this->toEmail = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        return $this->sendMessage($this->toEmail, $subject, $view, $params);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     *
     * @return bool
     */
    public function sendMessage($to, $subject, $view, $params = [])
    {
        $mailer = $this->_mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }
        return $mailer->compose(['html' => $view . '-html', 'text' => $view . '-text'],
            $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}
