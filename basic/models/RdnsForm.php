<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;

/**
 * ContactForm is the model behind the contact form.
 */
class RdnsForm extends Model
{
    public $ptr;
    public $dns1;
    public $dns2;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ptr', 'dns1', 'dns2'], 'required'],
            [['ptr', 'dns1', 'dns2'], 'trim'],
            [['ptr', 'dns1', 'dns2'], 'dnsFilter'],
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

    public function editRdns()
    {
        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){
                $data = array(
                    'user_id' => Yii::$app->user->id,
                    'ptr' => $this->ptr,
                    'dns1' => $this->dns1,
                    'dns2' => $this->dns2,
                );

                Rdns::insertDNS($data);
                return true;
            }else{
                return false;
            }

        }
        return false;
    }




}
