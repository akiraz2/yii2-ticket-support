<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\jobs;

use akiraz2\support\Mailer;
use akiraz2\support\models\Ticket;
use akiraz2\support\traits\ModuleTrait;
use yii\base\BaseObject;

class FetchMailJob extends BaseObject implements \yii\queue\JobInterface
{
    use ModuleTrait;

    public function execute($queue)
    {
        $this->getModule()->fetchMail();
    }
}