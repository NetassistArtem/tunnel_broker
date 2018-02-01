<?php
use yii\helpers\Html;

$end_registration_link = Yii::$app->urlManager->createAbsoluteUrl(['site/end-registration', 'token'=>$new_mail->registration_token]);
?>

<div class="password-reset">
    <p>Hello,</p>
    <p>Thank you for your NetAssist IPv6 Tunnel Broker registration!</p>
    <p>To complete registration, please confirm your e-mail by visiting (clicking) URL:</p>
    <p><?= Html::a(Html::encode($end_registration_link), $end_registration_link) ?></p>
    <p>Feel free to ask our support for any related questions!</p>
    <p></p>
    <p>WBR,</p>
    <p>NetAssist IPv6 Tunnel Broker Support Team.</p>
</div>