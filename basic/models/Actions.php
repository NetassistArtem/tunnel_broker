<?php
namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\User;



class Actions extends  ActiveRecord{

    public static function tableName()
    {
        return '{{actions}}';
    }


    public static function insertAction( $user_data,$action, $new_ip = 0, $new_email = 0){

        if(!empty($user_data)){
            $action_obj = new Actions();
            if(isset($user_data->id)){
                $action_obj->user_id = $user_data->id;
            }

            $action_obj->ip_db = isset($user_data->id) ? $user_data->ip : ip2long($user_data->ip);
            $action_obj->ip_real = ip2long(Yii::$app->request->userIP);
            $action_obj->email = isset($user_data->email) ? $user_data->email : $user_data->login;
            $action_obj->created_at = time();
            $action_obj->action = $action;
            if(isset($user_data->admin)){
                $action_obj->by_admin = $user_data->admin;
            }
            if($new_ip){
                $action_obj->ip_db_new = ip2long($new_ip) ;
            }
            if($new_email){
                $action_obj->email_new = $new_email;
            }

            $action_obj->save();
        }

    }


}