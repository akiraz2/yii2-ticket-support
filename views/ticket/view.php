<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

use akiraz2\support\models\Ticket;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model akiraz2\support\models\Ticket */
/* @var $reply akiraz2\support\models\Content */

\akiraz2\support\assets\TicketAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => \akiraz2\support\Module::t('support', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ticket-view">
    <div class="box box-info">
        <div class="box-header with-border">
            <h2 class="box-title">Ticket &num;<?= $model->hash_id ?> <?= $model->title ?>
                <small style="margin-left: 10px"><?= $model->category ? $model->category->title : '-' ?></small>
                <small style="margin-left: 10px"><?= $model->getType() ?></small>
            </h2>

            <div class="pull-right">
                <?= \akiraz2\support\Module::t('support', 'Status: {STATUS}',
                    ['STATUS' => $model->getStatusColorText()]) ?>
            </div>

        </div>
        <div class="box-body">
            <ul class="timeline timeline-inverse">
                <?php foreach ($model->contents as $post) {
                    ?>
                    <li>
                        <?php if (empty($post->user_id)): ?>
                            <i class="fa fa-info-circle bg-aqua"></i>
                        <?php else: ?>
                            <?= Html::tag('i', '',
                                ['class' => $post->getIsOwn() ? 'fa fa-comments bg-blue' : 'fa fa-comments bg-orange']) ?>
                        <?php endif; ?>

                        <div class="timeline-item">
                            <span class="time"><i
                                    class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asDatetime($post->createdAt) ?></span>
                            <h3 class="timeline-header"><?= $post->getUsername() ?></h3>
                            <div class="timeline-body">
                                <?= Yii::$app->formatter->asHtml($post->content) ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
                <li>
                    <i class="fa fa-clock-o"></i>
                </li>
            </ul>


            <div style="padding-top: 10px">
                <?php $form = ActiveForm::begin(); ?>
                <?php
                if (Yii::$app->getModule('support')->getIsBackend()) {
                    echo $form->field($reply, 'content')->textarea()->label(false);
                } else {
                    echo $form->field($reply, 'content')->textarea()->label(false);
                }
                ?>
                <div class="form-group">
                    <?= \yii\helpers\Html::submitButton(\akiraz2\support\Module::t('support', 'Reply'),
                        ['class' => 'btn btn-primary']) ?>

                    <?php if ($model->status != Ticket::STATUS_CLOSED): ?>
                        <?= Html::a(\akiraz2\support\Module::t('support', 'Close'), [
                            'close',
                            'id' => $model->hash_id
                        ], ['class' => 'btn btn-warning']) ?>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
