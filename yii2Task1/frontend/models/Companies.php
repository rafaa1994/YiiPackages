<?php

namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\Admin;

/**
 * Companies model
 *
 * @property integer $id
 * @property string $name
 * */
class Companies extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%companies}}';
    }

    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['company_id' => 'company_id']);
    }

    public static function findIdentity($id) {
        return static::findOne(['company_id' => $id]);
    }

}
