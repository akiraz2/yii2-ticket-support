<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \akiraz2\support\models\Ticket */
?>

<?= \akiraz2\support\Module::t('support', 'Hello Admin,') ?>

<?= \akiraz2\support\Module::t('support', '{USER} ({EMAIL}) have opened a ticket with the following message:', [
    'USER' => Html::encode($model->user->{\Yii::$app->getModule('support')->userName}),
    'EMAIL' => Html::encode($model->user->{\Yii::$app->getModule('support')->userEmail})
]) ?>


<?= $model->title ?>

<?= Yii::$app->formatter->asNtext($model->content) ?>



<?= \akiraz2\support\Module::t('support', 'View Ticket: {URL}', ['URL' => $model->getUrl(true)]) ?>