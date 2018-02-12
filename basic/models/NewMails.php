<?php
namespace app\models;

use app\components\debugger\Debugger;
use Codeception\Lib\Connector\Yii2;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class NewMails extends  ActiveRecord{

    private static function toTimestamp($time){
        return Yii::$app->formatter->asTimestamp($time);
    }

    public static function tableName()
    {
        return '{{new_mails}}';
    }

    public static function getDataByEmail($email){

        return self::findOne(['email' => $email]);
    }

    public static function isEmailExist($email){

        return self::findOne(['email' => $email])? true : false;
    }

    public static function updateIpToken($email,$ip,$registration_token){
        $mail_data = self::getDataByEmail($email);
        if($mail_data){
            $mail_data->ip = ip2long($ip);
            $mail_data->registration_token = $registration_token;
            $mail_data->created_at = time();
            $mail_data->save();
        }

    }

    public static function newMail( $email, $ip, $registration_token){

            $new_mail = new NewMails();
            $new_mail->email = $email;
            $new_mail->ip = ip2long($ip) ;
            $new_mail->registration_token = $registration_token ;
            $new_mail->created_at = time();
            $new_mail->save();


    }

    public static function findByRegistrationToken($token)
    {

        if (!static::isRegistrationTokenValid($token)) {
            return null;
        }

        return static::findOne(['registration_token' => $token]);
    }

    public static function isRegistrationTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['registrationTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generateRegistrationToken()
    {
        $this->registration_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->registration_token = null;
    }
    public static function removeData($email)
    {
        $data = self::getDataByEmail($email);
        $data->delete();
    }

    public static function removeOldEmails()
    {
        $params = array(
            ':time_now' => time() - Yii::$app->params['registrationTokenExpire']
        );

        $sql_query = 'DELETE FROM  `new_mails` WHERE    `created_at` < :time_now ';
        Yii::$app->db->createCommand($sql_query)->bindValues($params)->execute();

    }




}