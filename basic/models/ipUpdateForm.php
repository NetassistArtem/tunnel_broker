<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;
//use yii\web\User;
use app\models\User;

/**
 * ContactForm is the model behind the contact form.
 */
class ipUpdateForm extends Model
{
    public $ip;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip'], 'trim'],
            [['ip'], 'ipValidator'],
            [['ip'], 'uniqueIp'],
        ];
    }

    public static function ipValidationMain($ip)
    {
        if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip)) {
            //now all the intger values are separated
            $parts=explode(".",$ip);
            //now we need to check each part can range from 0-255
            foreach($parts as $ip_parts)
            {
                if(intval($ip_parts)>255 || intval($ip_parts)<0){
                    return false;
                    //  return false; //if number is not within range of 0-255
                }

            }
            //return true;
        } else{
            return false;
            //return false; //if format of ip address doesn't matches
        }
        return true;
    }

    public static function uniqueIPMain($ip)
    {
        if (User::isUserExistByIp($ip)) {
            return false;
        }
        return true;
    }

    public function ipValidator($attribute, $params){
        if (!$this->hasErrors()){

            if(!self::ipValidationMain($this->$attribute)){
                $this->addError($attribute, 'Incorrect IPv4 address');
            }
        }
    }


    public function uniqueIp($attribute, $params){
        if (!$this->hasErrors()){
            if (!$this->uniqueIPMain($this->$attribute)) {
                $this->addError($attribute, 'IPv4 address '.$this->$attribute.' is already registered in our system for another customer!');
            }
        }
    }



    public function editIp($user_data)
    {
        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){
                User::updateUserIp($user_data->id,$this->ip);

                return true;
            }else{
                return false;
            }

        }
        return false;
    }




}
