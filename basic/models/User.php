<?php

namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface


{
    /*
    public $id;
    public $username;
    public $password;
    public $auth_key;
    //public $accessToken;
    public $email;
    public $ip;
    public $created_at;
    public $updated_at;
    public $password_reset_token;
*/
    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    //    foreach (self::$users as $user) {
      //      if (strcasecmp($user['username'], $username) === 0) {
        //        return new static($user);
          //  }
       // }
       // $te =  static::findOne(['username' => $username])->password_hash;
   //    Debugger::PrintR(self::findOne(['username' => $username]));
       // Debugger::PrintR(self::find()->where(['username' => $username])->one());
      //  Debugger::EhoBr($te);
       // Debugger::testDie();
        return static::findOne(['username' => $username]);

//        return null;
    }

    public static function findUserById($id){
        return self::findOne(['id' => $id]);
    }
    public function getUserById($id){
        return $this->findOne(['id' => $id]);
    }

    public static function isUserExistByEmail($email){

        return self::findOne(['email' => $email])? true : false;
    }

    public static function isUserExistByIp($ip){

        return self::findOne(['ip' => ip2long($ip)])? true : false;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
      //  Debugger::EhoBr('test');
       // Debugger::EhoBr($this->password_hash);
       // Debugger::testDie();
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function authoGenerationPassword(){

        return Yii::$app->security->generateRandomString(16);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function insertNewUser( $new_mail, User $new_user)
    {
       // $new_user = new User();
        $this->id = self::findEmptyId();
        $new_user->username = $new_mail->email;
       // $new_user->password = $password_hash;
        $new_user->auth_key = $this->generateAuthKey();
        $new_user->email = $new_mail->email;
        $new_user->ip = $new_mail->ip;
        $new_user->created_at = time();
        $new_user->save();


    /*
    public $id;
    public $username;
    public $password;
    public $auth_key;
    //public $accessToken;
    public $email;
    public $ip;
    public $created_at;
    public $updated_at;
    public $password_reset_token;
*/

    }

    public static function findEmptyId()
    {
        $id_array = self::find()->asArray()->all();
        foreach($id_array as $k => $v){
            if(($k+1)< $v['id']){
                return $k+1;
            }

        }
        return count($id_array)+1;
    }

    public static function updateUserIp($user_id, $new_ip)
    {

        $user = self::findOne($user_id);
        $user->ip =  ip2long($new_ip);

        $user->update();
       // Debugger::PrintR($user);
       // Debugger::EhoBr(ip2long($new_ip));
       // Debugger::testDie();

    }

    public static function deleteUser($user_id)
    {
        $user = self::findOne($user_id);
        $user->delete();

    }
    public static function isAdmin()
    {
        $user_data = Yii::$app->session->get('user_data');
        if(empty($user_data)){
            return false;
        }
        return $user_data->admin ? true:false;
    }

    public static function getUsersList()
    {
        $users = self::find()->asArray()->all();

        return $users;
    }

    public static function addAdminRules($user_id)
    {
        $user = self::findOne($user_id);
        $user->admin = 1;
        $user->update();
    }


}
