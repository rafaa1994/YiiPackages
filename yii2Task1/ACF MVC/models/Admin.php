<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\models\Company;
use yii\behaviors\SluggableBehavior;

/**
 * Admin model
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $slug
 * @property integer $company_id
 * @property string $email
 * @property string $password write-only password
 * @property integer $role
 */
class Admin extends ActiveRecord implements IdentityInterface {

    const ROLE_ADMIN = 10;
    const ROLE_ROOT = 20;

   

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%administrator}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => true,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['role', 'default', 'value' => 10],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_ROOT]],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],
            ['company_id', 'integer'],
        ];
    }

    public function attributeLabels() {

        return
                [
                    'company_id' => 'Company'
        ];
    }
    
    /*
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    public static function findByUsername($username) {
        return static::findOne(['name' => $username]);
    }

    public static function findByEmail($email) {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     * */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getCompany() {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function isAdmin($id) {

        if (static::findOne(['id' => $id, 'role' => self::ROLE_ADMIN])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isRootAdmin($id) {

        if (static::findOne(['id' => $id, 'role' => self::ROLE_ROOT])) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkByAdminId($id) {
        if (static::findOne(['id' => $id])) {
            return true;
        } else {
            return false;
        }
    }

}
