<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Login';
?>


<div class="col-sm-offset-2 col-md-offset-3 col-lg-offset-3 col-sm-8 col-md-6 col-lg-6 site-login">
    <div class="panel panel-default custom-panel-style" >
        <div class="panel-heading" >
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body"  >
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6 col-md-6 col-lg-6\">{input}</div>\n<div class=\"col-sm-3 col-md-3 col-lg-3\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-3 col-md-3 col-lg-3 control-label'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-lg-8 col-md-8 col-sm-8">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-custom', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="col-lg-offset-1" style="color:#999;">
                <p>
                    If you forgot your password you can <?= Html::a('RESET PASSWORD', ['site/request-password-reset']) ?>.
                </p>



            </div>
            <div class="col-lg-offset-1" style="color:#999;" >
                <p>
                    If you new user:  <?= Html::a('REGISTRATION', ['site/registration']) ?>
                </p>

            </div>
        </div>

    </div>





</div>
