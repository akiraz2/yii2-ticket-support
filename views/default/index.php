<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

$this->title = \akiraz2\support\Module::t('support', 'Ticket System');
?>
<div class="ticket-default-index">
    <div class="clearfix" style="margin-bottom: 10px;">
        <div class="pull-right">
            <?= \yii\helpers\Html::a('fetch mail', ['default/fetch-mail'], ['class' => 'btn btn-sm btn-warning']);?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-question-circle"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= \akiraz2\support\Module::t('support', 'New Tickets Today');?></span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-fire"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= \akiraz2\support\Module::t('support', 'Unanswered tickets');?></span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2><?= \akiraz2\support\Module::t('support', 'Categories');?></h2>
            <?= \yii\helpers\Html::a(\akiraz2\support\Module::t('support', 'Categories'), ['category/index']);?>
        </div>
        <div class="col-md-6">
            <h2><?= \akiraz2\support\Module::t('support', 'Last tickets');?></h2>
            <?= \yii\helpers\Html::a(\akiraz2\support\Module::t('support', 'Last tickets'), ['ticket/manage']);?>
        </div>
    </div>
</div>
