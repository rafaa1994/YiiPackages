<?php

namespace frontend\models;

use common\models\Admin;
use yii\base\Model;
use Yii;



class Filter extends Model {
    

    public $radio = ['radio_name' => true, 'radio_surname' => false,'radio_company' => false,'radio_email' => false];
    
    public function rules() {
        
        return [
            ['radio_name', 'boolean']
        ];
        
    }
    
    
    public function getStateRadioName(){
        return $this->radio['radio_name'];
    }
    
    public function getStateRadioSurname(){
        return $this->radio['radio_surname'];
    }
    
    public function getStateRadioCompany(){
    return $this->radio['radio_company'];
    }
    
    public function getStateRadioEmail(){
        return $this->radio['radio_email'];
    }
    
}