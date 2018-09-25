<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\jobs;

use akiraz2\support\Mailer;
use akiraz2\support\models\Content;
use akiraz2\support\traits\ModuleTrait;
use yii\base\BaseObject;

class SendMailJob extends BaseObject implements \yii\queue\JobInterface
{
    use ModuleTrait;

    public $contentId;

    public function execute($queue)
    {
        $content = Content::findOne(['id' => $this->contentId]);
        if ($content !== null) {
            $email = $content->ticket->user_contact;
            /* send email */
            $subject = \akiraz2\support\Module::t('support', '[{APP} Ticket #{ID}] Re: {TITLE}',
                ['APP' => Yii::$app->name, 'ID' => $content->ticket->hash_id, 'TITLE' => $content->ticket->title]);
            $this->mailer->sendMessage(
                $email,
                $subject,
                'reply-ticket',
                ['title' => $subject, 'model' => $content]
            );
        }
    }

    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }
}