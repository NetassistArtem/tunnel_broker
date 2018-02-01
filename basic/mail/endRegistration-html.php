<?php
use yii\helpers\Html;

$end_registration_link = Yii::$app->urlManager->createAbsoluteUrl(['site/main']);
?>

<div class="password-reset">
    <p>Hello,</p>
    <p>You have registered for NetAssist IPv6 Tunnel Broker.</p>
    <p>Your login is your <b><?= $data['email'] ?></b>  and your password is: <b><?= $data['password'] ?></b>.</p>
    <p>You can log in into NetAssist IPv6 Tunnel Broker web site <?=$end_registration_link ?> with your login and password,
        and see your IPv6 address, examples of the configuration for different sites, etc.</p>
    <p>Feel free to ask our support for any related questions!</p>
    <p>WBR,</p>
    <p>NetAssist IPv6 Tunnel Broker Support Team.</p>
</div>
