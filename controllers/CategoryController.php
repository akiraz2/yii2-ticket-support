<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */

namespace akiraz2\support\controllers;

use akiraz2\support\components\BackendFilter;
use akiraz2\support\models\Category;
use akiraz2\support\models\CategorySearch;
use akiraz2\support\Module;
use akiraz2\support\traits\ModuleTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for Categorymodel.
 */
class CategoryController extends Controller
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $array = [];
        if (!$this->getModule()->yii2basictemplate) {
            $array = ['backend' => [
                'class' => BackendFilter::className(),
                'actions' => [
                    '*',
                ],
            ]];
        }
        
        return array_merge($array, [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->getModule()->adminMatchCallback;
                        },
                    ]
                ],
            ],
        ]);
    }

    /**
     * Lists all Categorymodels.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = Module::t('support', 'Categories');
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categorymodel.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $this->view->title = \akiraz2\support\Module::t('support', 'Category: {NAME}', ['NAME' => $model->title]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Categorymodel based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categorythe loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Creates a new Categorymodel.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->view->title = \akiraz2\support\Module::t('support', 'Add Category');
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => is_a($model, '\yii\mongodb\ActiveRecord') ? (string)$model->id : $model->id
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Categorymodel.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->view->title = \akiraz2\support\Module::t('support', 'Update: {NAME}', ['NAME' => $model->title]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => is_a($model, '\yii\mongodb\ActiveRecord') ? (string)$model->id : $model->id
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categorymodel.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
