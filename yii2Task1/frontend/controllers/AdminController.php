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
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->can('update', ['id' => Yii::$app->user->id]));
                        }
                    ],
                ],
              'denyCallback' => function ($rule, $action) {
               throw new \Exception(Yii::t('app','Nie masz uprawień do tej strony'));
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
        $beforeUpdateRole = $model->role;

        if (Yii::$app->user->can('update', ['id' => $id])) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->changeRole($model,$beforeUpdateRole);
                return $this->render('view', [
                            'model' => $model,
                ]);
            }
            
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
        return $this->redirect(['admin/search']);
    }

    protected function findModel($id) {

        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function changeRole($model,$role){
        $auth = Yii::$app->authManager;
         if ($model->role != $role){
                $role = ($model->role == 20) ? $auth->getRole('Root') : $auth->getRole('Admin');
                $auth->revokeAll($model->id);
                $auth->assign($role, $model->id);
            }
    }

}
