<?php
namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Auth extends  ActiveRecord{

    public static function tableName()
    {
        return '{{auth}}';
    }

    public static function getUserAuth($user_id){

        return self::find()->asArray()->where(['user_id' => $user_id])->all();
    }

    public static function newAuth($auth_data){
        if(!empty($auth_data)){
            $auth = new Auth();
            $auth->user_id = $auth_data['user_id'];
            $auth->ip = ip2long($auth_data['ip']) ;
            $auth->ip_forwarded = ip2long($auth_data['ip_forwarded']) ;
            $auth->save();
        }

    }

    public static function getAuthInfo(){
        if(Yii::$app->user->isGuest){
            return array();
        }else{
            $auth_data = array(
                'user_id' => Yii::$app->user->id,
                'ip' => Yii::$app->request->userIP,
                'ip_forwarded' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']: null,
            );
            return$auth_data;
        }
    }
}