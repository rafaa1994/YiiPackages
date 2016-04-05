<?php

namespace frontend\models;

use common\models\Admin;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
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
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 255],
            ['company_id', 'filter', 'filter' => 'trim'],
            ['company_id', 'required'],
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

        $user = new Admin();
        $user->username = $this->username;
        $user->surname = $this->surname;
        $user->company_id = $this->company_id;
        $user->email = $this->email;
        $user->setPassword($this->password);

        return $user->save() ? $user : null;
    }

}
