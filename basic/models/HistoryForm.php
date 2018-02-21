<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;
use app\models\User;

/**
 * ContactForm is the model behind the contact form.
 */
class HistoryForm extends Model
{
    public $all_users = array();
    public $time_from;
    public $time_to;
    public $action;




    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['time_from','time_to','action'], 'required'],
            [['time_from','time_to'], 'default','value' => null],
            [['time_from','time_to'], 'date','format' => 'yyyy-MM-dd'],
        ];
    }



    public function history($user_id)
    {
      //  Debugger::PrintR($_POST['HistoryForm']['all_users']);

        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){




                return true;
            }else{
                return false;
            }

        }
        return false;
    }




}
