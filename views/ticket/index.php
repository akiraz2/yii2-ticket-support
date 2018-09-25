<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

use akiraz2\support\models\Category;
use akiraz2\support\models\Ticket;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel akiraz2\support\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');

?>
<div class="ticket-index">
    <div class="box box-primary">
        <div class="box-body">
            <p>
                <?= Html::a(\akiraz2\support\Module::t('support', 'Open Ticket'), ['create'],
                    ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                return $model->category->title;
                            },
                            'filter' => Category::getCatList()
                        ],
                        'title',

                        //['attribute' => 'user_id', 'value' => function ($model){return $model->user->{\Yii::$app->getModule('support')->userName};}],
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                return $model->statusColorText;
                            },
                            'filter' => Ticket::getStatusOption(),
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => 'createdAt',
                            'format' => 'dateTime',
                            'filter' => DatePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'dateFormat' => 'yyyy-MM-dd',
                                'options' => ['class' => 'form-control']
                            ]),
                            'contentOptions' => ['style' => 'min-width: 80px']
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return \yii\helpers\Url::to([$action, 'id' => $model->hash_id]);
                            }
                        ],

                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay grid-view-overlay hidden">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->
    </div>
</div>
