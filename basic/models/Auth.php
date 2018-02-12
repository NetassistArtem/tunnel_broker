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
            $auth->ip_real = ip2long($auth_data['ip_real']) ;
            $auth->ip_db = $auth_data['ip_db'] ;
            $auth->ip_forwarded = ip2long($auth_data['ip_forwarded']) ;
            $auth->email = $auth_data['email'];
            $auth->created_at = time();
            $auth->save();
        }

    }


}