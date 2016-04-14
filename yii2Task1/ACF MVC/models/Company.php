<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\Admin;

/**
 * Companies model
 *
 * @property integer $id
 * @property string $name
 * 
 **/


class Company extends ActiveRecord {

    /**
     * 
     * @inheritdoc
     */
    
    public static function tableName() {
        return '{{%companies}}';
    }

    /**
     * @
     * @return type
     */
    public function getAdmins() {
        return $this->hasMany(Admin::className(), ['company_id' => 'id']);
    }

   
    /**
     * 
     * @param type $id
     * @return type
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    public function getRandomCompanyID() {
        $companies = Company::find()->asArray()->all();
        $rand = mt_rand(0, count($companies));
        $company_id = $companies[$rand];
        return (int)$company_id['id'];
    }
    
}
