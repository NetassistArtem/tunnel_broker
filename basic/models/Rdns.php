<?php
namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\Actions;


class Rdns extends  ActiveRecord{

    public static function tableName()
    {
        return '{{rdns}}';
    }

    public static function getDNSById($user_id){

        return self::findOne($user_id);
    }

    public static function insertDNS2($data){

        if(!empty($data)){
            self::deleteDNS($data['user_id']);
           $rdns = new Rdns();
           $rdns->user_id = $data['user_id'];
            $rdns->ptr = $data['ptr'];
            $rdns->dns1 = $data['dns1'];
            $rdns->dns2 = $data['dns2'];
           $rdns->save();
        }

    }

    public static function insertDNS($user_data, $data){


        if(!empty($data)){
            $rdns = self::getDNSById($user_data->id);
            if($rdns){
                if(isset($data['ptr'])){
                    $rdns->ptr = $data['ptr'];
                    $rdns->update();
                    Actions::insertAction($user_data, 9);
                }elseif(isset($data['dns1']) && isset($data['dns2'])){

                    $rdns->dns1 = $data['dns1'];
                    $rdns->dns2 = $data['dns2'];
                    $rdns->update();

                    Actions::insertAction($user_data, 7);

                }

            }else{
                $rdns = new Rdns;

                $rdns->user_id = $user_data->id;
                $rdns->ptr = isset($data['ptr'])? $data['ptr'] : '' ;
                $rdns->dns1 = isset($data['dns1'])? $data['dns1'] : '' ;
                $rdns->dns2 = isset($data['dns2'])? $data['dns2'] : '' ;
                $rdns->save();
              //  Debugger::EhoBr($rdns->user_id);
                // Debugger::PrintR($data);
                //Debugger::testDie();

                if(isset($data['ptr'])){
                    Actions::insertAction($user_data,8);
                }else{
                    Actions::insertAction($user_data, 6);
                }
            }
        }
    }

    public static function deleteDNS($user_id)
    {
       // $rdns_del = new Rdns();
        $rdns_del = self::findOne($user_id);

if(!empty($rdns_del)){
    $rdns_del->delete();
}


    }



}