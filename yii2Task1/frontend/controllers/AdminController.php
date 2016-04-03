<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Admin;
use frontend\models\Filter;
use common\models\LoginForm;
use frontend\models\SignupForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
    public function actions()
    {
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
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
    
    public function actionLogout(){
        
            Yii::$app->user->logout();
        
            return $this->goHome(); 
        }
           
    

        /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            //$model->slug = $name;
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
    
    public function actionRegisteredUsers(){
        
        
        $query = Admin::find();
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);
        
        $filter = new Filter();
        
        if ($filter->load(Yii::$app->request->post()))
        {
        
            if($filter->getStateRadioName()){
            $registeredUsers = $query->orderBy('name')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
        
            if($filter->getStateRadioSurname()){
            $registeredUsers = $query->orderBy('surname')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
        
            if($filter->getStateRadioCompany()){
            $registeredUsers = $query->orderBy('company')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
        
            if($filter->getStateRadioEmail()){
            $registeredUsers = $query->orderBy('email')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
        
        
        }else{
            
            //default
            
            $registeredUsers = $query->orderBy('name')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
            
        
        return $this->render('registeredUsers', 
                [ 'pagination' => $pagination ,
                  'filter' => $filter, 
                  'registeredUsers' => $registeredUsers,
                ]);
         
        
    }

}
