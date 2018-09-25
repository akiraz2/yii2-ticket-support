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
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel akiraz2\support\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

\akiraz2\support\assets\TicketAsset::register($this);

/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');

?>
<div class="ticket-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php Pjax::begin(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //'id',
                        'hash_id',
                        [
                            'attribute' => 'category_id',
                            'value' => function ($model) {
                                return $model->category ? $model->category->title : '';
                            },
                            'filter' => Category::getCatList()
                        ],
                        'title',
                        //'user_id',
                        // 'created_at',
                        // 'updated_at',
                        [
                            'attribute' => 'user_id',
                            'value' => function ($model) {
                                $module = Yii::$app->getModule('support');
                                if ($model->user && $module->urlViewUser) {
                                    return \yii\helpers\Html::a($model->user_name,
                                        Yii::$app->urlManager->createUrl([
                                            $module->urlViewUser,
                                            $module->userPK => $model->user_id
                                        ]),
                                        ['data-pjax' => 0]);
                                } else {
                                    return $model->getNameEmail();
                                }
                            },
                            'format' => 'raw'
                        ],
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
                            'template' => '{view} {delete}',
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
