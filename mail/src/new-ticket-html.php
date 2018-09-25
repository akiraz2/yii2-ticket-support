<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

/* @var $this yii\web\View */

use yii\helpers\Html;

/* @var $model \akiraz2\support\models\Ticket */

?>
<div itemscope itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->getUrl(true) ?>"/>
        <meta itemprop="name" content="<?= \akiraz2\support\Module::t('support', 'View Ticket') ?>"/>
    </div>
    <meta itemprop="description"
          content="<?= \akiraz2\support\Module::t('support', 'You\'ve received a ticket (#{ID}) from {APP}',
              ['ID' => $model->id, 'APP' => Yii::$app->name]) ?>"/>
</div>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        <?= \akiraz2\support\Module::t('support', 'Greetings from {APP},',
                                            ['APP' => Yii::$app->name]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <?= \akiraz2\support\Module::t('support',
                                            '{USER} ({EMAIL}) have opened a ticket with the following message:',
                                            [
                                                'USER' => Html::encode($model->user->{\Yii::$app->getModule('support')->userName}),
                                                'EMAIL' => Html::encode($model->user->{\Yii::$app->getModule('support')->userEmail})
                                            ]) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="content-block">
                                        <?= \akiraz2\support\Module::t('support', 'Ticket #{ID}',
                                            ['ID' => $model->id]) ?>
                                        <br>
                                        <?= \akiraz2\support\Module::t('support', 'Subject: {TITLE}',
                                            ['TITLE' => $model->title]) ?>
                                        <br>
                                        <?= Yii::$app->formatter->asNtext($model->content) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="content-block aligncenter" colspan="2">
                                        <a href="<?= $model->getUrl(true) ?>"
                                           class="btn-primary"><?= \akiraz2\support\Module::t('support',
                                                'View Ticket') ?></a>
                                    </td>
                                </tr>


                            </table>
                        </td>
                    </tr>
                </table>

            </div>

        </td>
        <td></td>
    </tr>
</table>
<link href="src/css/mailgun.css" media="all" rel="stylesheet" type="text/css"/>