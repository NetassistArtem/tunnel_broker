<?php
namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
//use yii\web\User;
use app\models\User;
use app\controllers\AdminController;
use app\models\Auth;


class Actions extends ActiveRecord
{

    public static function tableName()
    {
        return '{{actions}}';
    }


    public static function insertAction($user_data, $action, $new_ip = 0, $new_email = 0)
    {

        if (!empty($user_data)) {
            $action_obj = new Actions();
            if (isset($user_data->id)) {
                $action_obj->user_id = $user_data->id;
            }

            $action_obj->ip_db = isset($user_data->id) ? $user_data->ip : ip2long($user_data->ip);
            $action_obj->ip_real = ip2long(Yii::$app->request->userIP);
            $action_obj->email = isset($user_data->email) ? $user_data->email : $user_data->login;
            $action_obj->created_at = time();
            $action_obj->action = $action;
            $logged_user = User::findUserById(Yii::$app->user->id);
           // Debugger::PrintR($logged_user);
           // Debugger::EhoBr();
            if (isset($logged_user->admin)) {
                $action_obj->by_admin = 1;
            }
            if ($new_ip) {
                $action_obj->ip_db_new = ip2long($new_ip);
            }
            if ($new_email) {
                $action_obj->email_new = $new_email;
            }

            $action_obj->save();
        }

    }

    public static function getUserHistory($user_email, $action, $time_from, $time_to)
    {
      //  Debugger::EhoBr($time_from);
       // Debugger::EhoBr($time_to);
       // Debugger::EhoBr($action);
       // Debugger::EhoBr($user_email);


        $param_array = [];

        if ($user_email) {
            $param_array['email'] = $user_email;
          //  $actions = self::find()->where(['user_id' => $user_id, 'action' => $action])->andWhere(['between', 'created_at', $time_from, $time_to])->asArray()->all();
        }
        if($action != 12 && $action != 13){
            $param_array['action'] = $action;

        }
        if($action ==12){
          return  Auth::getUserAuth($user_email,$time_from,$time_to);
        }
        $authentication = [];
        if($action == 13){
            $authentication = Auth::getUserAuth($user_email,$time_from,$time_to);
        }




        if(empty($param_array)){
            $actions_without_auth = self::find()->where(['between', 'created_at', $time_from, $time_to])->asArray()->all();
            $actions = array_merge($actions_without_auth, $authentication);
        }else{

          //  $user_id = Yii::$app->session->get('search-user-id');

// W=
        //    W3EdzXP3l17iLEn
            //$user = new User();
            // $user_data = $user->getUserById($user_id);

           // $params = array(
             //   ':email' => $user_data->email,
             //   ':time_from' =>  (int)$time_from,
             //   ':time_to' => (int)$time_to
           // );
           // Debugger::PrintR($params);
       //     Debugger::EhoBr(Yii::$app->formatter->asDate($time_from,'yyyy-MM-dd'));
     //       Debugger::EhoBr(Yii::$app->formatter->asDate($time_to,'yyyy-MM-dd'));

   //         $actions = Yii::$app->db->createCommand("select * from actions
 //where created_at between :time_from and :time_to and email= :email")
               // ->bindValues($params)
             //   ->queryAll();
            //  ->rawSql;
           // Debugger::Eho($actions);
           // Debugger::testDie();
            //select * from actions where created_at between '1517443200' and '1518566400' and email= 'testuser@test.com.ua'
//select * from actions where created_at between 1517443200 and 1518566400 and email= 'testuser@test.com.ua'
            //select * from actions where created_at between 1516108160 and 1518700160 and email= 'testuser@test.com.ua'




//r select * from actions where created_at between 1516109206 and 1518701206 and email= 'testuser@test.com.ua'
// nr select * from actions where created_at between 1515974400 and 1518652800 and email= 'testuser@test.com.ua'
           // 1518687206
            //select * from actions where created_at between 1517443200 and 1518566400 and email= 'testuser@test.com.ua'
            $actions_without_auth = self::find()->where( $param_array)->andWhere(['between', 'created_at', $time_from, $time_to])->asArray()->all();
            $actions = array_merge($actions_without_auth, $authentication);
        }
       // Debugger::PrintR($param_array);
       // Debugger::VarDamp($user_email);



        return $actions;
    }


}