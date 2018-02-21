<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class FindUserForm extends Model
{
    public $email;
    public $ipv4;
    public $ipv6;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
           // [['email', 'ipv4', 'ipv6'], 'required'],
            [['email'], 'email'],
            ['ipv4', 'ip', 'ipv6' => false, 'subnet' => null],
            ['ipv6', 'ip', 'ipv4' => false, 'subnet' => null]
            // rememberMe must be a boolean value
          //  ['rememberMe', 'boolean'],
            // password is validated by validatePassword()

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function validateData()
    {
        if ($this->validate()) {
            if(!Yii::$app->user->isGuest){

                return true;
            }else{
                return false;
            }

        }
        return false;
    }

    public function getUserIdAction($searchable_array)
    {
        $action = Yii::$app->request->post('action') ? Yii::$app->request->post('action') : 0;
       // $user_id = $this->email ? $this->email : $this->ipv4
        if(!empty($this->email)){
            $user_id = array_search($this->email,$searchable_array['emails'] );
        }elseif(!empty($this->ipv4)){
            $user_id = array_search($this->ipv4,$searchable_array['ipv4'] );
        }elseif(!empty($this->ipv6)){
            $user_id = array_search($this->ipv6,$searchable_array['ipv6'] );
        }else{
            $user_id = null;
        }

        return array('action' => $action, 'user_id' => $user_id);

    }



}
