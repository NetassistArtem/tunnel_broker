<?php

namespace app\models;

use app\components\debugger\Debugger;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class EmailChangeRequestForm extends Model
{
    public $new_email;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            ['new_email', 'trim'],
            ['new_email', 'required'],
            ['new_email', 'email'],
            ['new_email', 'uniqueCustom'],
            ['new_email', 'blockedEmail'],

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

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $new_mail = new NewMails();
        $data_email = NewMails::getDataByEmail($this->new_email);
        $ip = ip2long(Yii::$app->request->userIP);
       // Debugger::testDie();
        if($data_email){
            // if (!NewMails::isRegistrationTokenValid($new_mail->registration_token)) {
            $new_mail->generateRegistrationToken();
            NewMails::updateIpToken($this->new_email, $ip, $new_mail->registration_token);
            //  Debugger::EhoBr($new_mail->registration_token);
            //  Debugger::testDie();
            //    if(!NewMails::newMail($this->login, $this->ip, $new_mail->registration_token)){
            // return false;

            //  }
            //}
        }else{
            $new_mail->generateRegistrationToken();
            NewMails::newMail($this->new_email, $ip, $new_mail->registration_token);
        }





        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailResetToken-html', 'text' => 'emailResetToken-text'],
                ['new_mail' => $new_mail]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->new_email)
            ->setSubject('NetAssist IPv6 Tunnel Broker e-mail verification')
            ->send();

    }

}