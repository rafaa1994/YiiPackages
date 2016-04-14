<?php

namespace frontend\models;

use common\models\Admin;
use frontend\models\Company;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $name;
    public $surname;
    public $email;
    public $password;
    public $company_id;

    public function behaviors() {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Admin', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return Admin|null the saved model or null if saving fails
     */
    public function signup() {


        if (!$this->validate()) {
            return null;
        }

        $company_id = new Company();
        $user = new Admin();
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->company_id = $company_id->getRandomCompanyID();
        $user->email = $this->email;
        $user->setPassword($this->password);
        
        $user->save();
        
        return ($user!= null) ? $user : null;
        
    }

}
