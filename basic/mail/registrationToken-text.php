<?php

$end_registration_link = Yii::$app->urlManager->createAbsoluteUrl(['site/end-registration', 'token'=>$new_mail->registration_token]);
?>

    Hello!

    Thank you for your NetAssist IPv6 Tunnel Broker registration!

    To complete registration, please confirm your e-mail by visiting (clicking) URL
    <?=$end_registration_link ?>

    Feel free to ask our support for any related questions!

    --
    WBR,
    NetAssist IPv6 Tunnel Broker Support Team.

