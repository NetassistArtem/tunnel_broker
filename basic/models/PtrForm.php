<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;
use app\models\User;

/**
 * ContactForm is the model behind the contact form.
 */
class PtrForm extends Model
{
    public $ptr;




    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ptr'], 'required'],
            [['ptr'], 'trim'],
            [['ptr'], 'dnsFilter'],
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

    public function editPtr(User $user_data)
    {
        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){
                $data_ptr = array(
                    'ptr' => $this->ptr,

                );

                Rdns::insertDNS($user_data, $data_ptr);
                return true;
            }else{
                return false;
            }

        }
        return false;
    }




}
