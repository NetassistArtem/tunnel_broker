<?php
use yii\helpers\Html;

$newEmailCheckLink = Yii::$app->urlManager->createAbsoluteUrl(['site/change-email', 'token' => $new_mail->registration_token]);
?>

<div class="password-reset">
    <p>Hello ,</p>
    <p>Follow the link below to Check your new e-mail:</p>
    <p><?= Html::a(Html::encode($newEmailCheckLink), $newEmailCheckLink) ?></p>
</div>