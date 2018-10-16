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
<?= \akiraz2\support\Module::t('support', 'Ticket #{ID}: New reply from {NAME}:', [
    'ID' => $model->ticket->hash_id,
    'NAME' => $model->getUsername()
]) ?>

<?= Yii::$app->formatter->asNtext($model->content) ?>
