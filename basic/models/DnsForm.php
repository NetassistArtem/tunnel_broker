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
class DnsForm extends Model
{

    public $dns1;
    public $dns2;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['dns1', 'dns2'], 'required'],
            [[ 'dns1', 'dns2'], 'trim'],
            [[ 'dns1', 'dns2'], 'dnsFilter'],
        ];
    }


    public function dnsFilter($attribute, $params)
    {
        if (!$this->hasErrors()) {


            if (!preg_match('|^[a-zA-Z0-9\.\-]*$|',$this->$attribute )) {
                $this->addError($attribute, 'Only symbols A-Z,a-z,.(dot) and -(dash) are permitted.');
            }
        }
    }

    public function editDns(User  $user_data)
    {
        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){
                $data_dns = array(
                    'dns1' => $this->dns1,
                    'dns2' => $this->dns2,

                );

                Rdns::insertDNS($user_data, $data_dns);
                return true;
            }else{
                return false;
            }

        }
        return false;
    }




}
