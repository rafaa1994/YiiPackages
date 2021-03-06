<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\SignupForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SearchAdmin;
use common\models\Admin;

/**
 * Site controller
 */
class AdminController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'search', 'update', 'view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['search', 'logout', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {

                    $id_param = (int) Yii::$app->request->queryParams['id'];
                    if (Admin::isRootAdmin(Yii::$app->user->id)) {
                        return true;
                    } else if (Admin::isAdmin(Yii::$app->user->id) && $id_param == Yii::$app->user->id) {
                        return true;
                    }
                    return false;
                }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {

            return $this->redirect(['admin/search']);
        }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {

        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionSearch() {


        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $searchModel = new SearchAdmin();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('search', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {

        $model = $this->findModel($id);
        return $this->render('view', [ 'model' => $model
        ]);
    }

    public function actionUpdate($id) {

        $model = $this->findModel($id);
        $company_id_origin = $model->company_id;
        $role_origin = $model->role;
        
      
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if (Yii::$app->user->identity->role == 10){
                $model->company_id = $company_id_origin;
                $model->role = $role_origin;
                $model->save();
            }
            
            return $this->render('view', [
                        'model' => $model,
            ]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    protected function findModel($id) {

        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
