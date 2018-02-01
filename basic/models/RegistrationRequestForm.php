<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\debugger\Debugger;
/**
 * Password reset request form
 */
class RegistrationRequestForm extends Model
{
    public $login;
    public $ip;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['login','ip'], 'trim'],
            [['login','ip'], 'required'],
            ['login', 'email'],
            ['login', 'uniqueCustom'],
            ['login', 'blockedEmail'],
            ['ip', 'ipValidator'],
            ['ip', 'uniqueIp']

        ];
    }

    public function uniqueCustom($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (User::isUserExistByEmail($this->$attribute)) {
                $this->addError($attribute, 'User with this e-mail is already registered in our system!');
            }
        }
    }

    public function ipValidator($attribute, $params){
        if (!$this->hasErrors()){
            if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$this->$attribute)) {
                //now all the intger values are separated
                $parts=explode(".",$this->$attribute);
                //now we need to check each part can range from 0-255
                foreach($parts as $ip_parts)
                {
                    if(intval($ip_parts)>255 || intval($ip_parts)<0){
                        $this->addError($attribute, 'Incorrect IPv4 address');
                        //  return false; //if number is not within range of 0-255
                    }

                }
                //return true;
            } else{
                $this->addError($attribute, 'Incorrect IPv4 address');
                //return false; //if format of ip address doesn't matches
            }

        }
    }
    public function blockedEmail($attribute, $params){

        if (!$this->hasErrors()){
            $blocked_mail_array = Yii::$app->params['blocked_email'];
            foreach($blocked_mail_array as $k => $v){
                $pos=strpos($this->$attribute,'@'.$v);
                if($pos !== false){
                    $this->addError($attribute, 'Do NOT use @'.$v.' e-mails! They was blocked our e-mail gate for some unknown reason!');
                }

            }
        }

    }

    public function uniqueIp($attribute, $params){
        if (!$this->hasErrors()){
            if (User::isUserExistByIp($this->$attribute)) {
                $this->addError($attribute, 'IPv4 address '.$this->$attribute.' is already registered in our system for another customer!');
            }
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $new_mail = new NewMails();
        $data_email = NewMails::getDataByEmail($this->login);
        if($data_email){
           // if (!NewMails::isRegistrationTokenValid($new_mail->registration_token)) {
                $new_mail->generateRegistrationToken();
                NewMails::updateIpToken($this->login, $this->ip, $new_mail->registration_token);
                //  Debugger::EhoBr($new_mail->registration_token);
                //  Debugger::testDie();
                //    if(!NewMails::newMail($this->login, $this->ip, $new_mail->registration_token)){
                // return false;

                //  }
            //}
        }else{
            $new_mail->generateRegistrationToken();
            NewMails::newMail($this->login, $this->ip, $new_mail->registration_token);
        }


        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'registrationToken-html', 'text' => 'registrationToken-text'],
                ['new_mail' => $new_mail]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->login)
            ->setSubject('NetAssist IPv6 Tunnel Broker e-mail verification')
            ->send();
    }

}