<?php
namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Rdns extends  ActiveRecord{

    public static function tableName()
    {
        return '{{rdns}}';
    }

    public static function getDNSById($user_id){

        return self::findOne(['user_id' => $user_id]);
    }

    public static function insertDNS($data){
        if(!empty($data)){
            $rdns = new Rdns();
            $rdns->user_id = $data['user_id'];
            $rdns->ptr = $data['ptr'];
            $rdns->dns1 = $data['dns1'];
            $rdns->dns2 = $data['dns2'];
            $rdns->save();
        }

    }



}