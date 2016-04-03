<?php
namespace frontend\models;

use common\models\Admin;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $surname;
    public $email;
    public $slug;
    public $password;
    public $company;
    

    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 255],
            
            ['company', 'filter','filter' => 'trim'],
            ['company', 'required'],
            

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Admin', 'message' => 'This email address has already been taken.'],

            //['slug', 'unique', 'targetClass' => '\common\models\Admin', 'when' => function($user) {return $user->}]
            
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return Admin|null the saved model or null if saving fails
     */
    
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Admin();
        $user->username = $this->username;
        $user->surname = $this->surname;
        $user->companies->name = $this->company;
        $user->slug = $this->company;
        $user->email = $this->email;
        $user->setPassword($this->password);
        
        return $user->save() ? $user : null;
    }
}
