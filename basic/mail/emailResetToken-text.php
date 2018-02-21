<?php

$newEmailCheckLink = Yii::$app->urlManager->createAbsoluteUrl(['site/change-email', 'token' => $new_mail->registration_token]);
?>

    Hello,
    Follow the link below to Change your e-mail:

<?= $newEmailCheckLink ?>