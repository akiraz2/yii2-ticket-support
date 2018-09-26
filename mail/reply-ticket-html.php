<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

/* @var $this yii\web\View */
/* @var $model \akiraz2\support\models\Content */

?>
<div itemscope="" itemtype="http://schema.org/EmailMessage">
    <div itemprop="potentialAction" itemscope="" itemtype="http://schema.org/ViewAction">
        <link itemprop="target" href="<?= $model->ticket->getUrl(true) ?>">
        <meta itemprop="name" content="<?= \akiraz2\support\Module::t('support', 'View Ticket') ?>">
    </div>
    <meta itemprop="description"
          content="<?= \akiraz2\support\Module::t('support', '{APP}: Ticket #{ID} updated',
              ['APP' => Yii::$app->name, 'ID' => $model->ticket->id]) ?>">
</div>

<table class="body-wrap" style="background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
    <tr>
        <td style="vertical-align: top;" valign="top"></td>
        <td class="container" width="600"
            style="vertical-align: top; display: block !important; max-width: 600px !important; margin: 0 auto !important; clear: both !important;"
            valign="top">
            <div class="content" style="max-width: 600px; margin: 0 auto; display: block; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0"
                       style="background-color: #fff; border: 1px solid #e9e9e9; border-radius: 3px;" bgcolor="#fff">
                    <tr>
                        <td class="content-wrap" style="vertical-align: top; padding: 20px;" valign="top">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;"
                                        valign="top">
                                        <?= \akiraz2\support\Module::t('support',
                                            'Ticket #{ID}: New reply from {NAME}:', [
                                                'ID' => $model->ticket->hash_id,
                                                'NAME' => $model->getUsername()
                                            ]) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="content-block" style="vertical-align: top; padding: 0 0 20px;"
                                        valign="top">
                                        <?= Yii::$app->formatter->asHtml($model->content) ?>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>

            </div>

        </td>
        <td style="vertical-align: top;" valign="top"></td>
    </tr>
</table>
