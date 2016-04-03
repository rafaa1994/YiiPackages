<?php

use yii\db\ActiveRecord;
use common\models\Admin;

class Companies extends ActiveRecord {
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%companies}}';
    }
    
    public function getAdmin(){
        return $this->hasMany(Admin::className(), ['company_id' => 'companies_id']);
    }
}