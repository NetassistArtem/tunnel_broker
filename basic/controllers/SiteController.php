<?php

namespace app\controllers;

use app\models\Auth;
use app\models\NewMails;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\RegistrationRequestForm;
use app\components\debugger\Debugger;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'main'],
                'rules' => [
                    [
                        'actions' => ['logout', 'main'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }



    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Auth::newAuth(Auth::getAuthInfo());

            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }




    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionMain()
    {

       // User::findEmptyId();
       // Debugger::PrintR(User::findEmptyId());
        //Debugger::EhoBr('test');
      //  Debugger::PrintR(NewMails::removeOldEmails());
        return $this->render('main');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordReset', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionRegistration()
    {
        $model_registration_request = new RegistrationRequestForm();

        if ($model_registration_request->load(Yii::$app->request->post()) && $model_registration_request->validate()) {
            if ($model_registration_request->sendEmail()) {
                Yii::$app->session->setFlash('success', 'We just sent you the e-mail with the link to complete registration process. Please read that e-mail and click on the link.');
                return $this->redirect('/login');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable registration you. Try again later.');
            }
        }

        return $this->render('registration', [
            'model_registration_request' => $model_registration_request,
            'ip' => Yii::$app->request->userIP,
        ]);
    }

    public function actionEndRegistration($token)
    {
       $data_new_mail = NewMails::findByRegistrationToken($token);
       // Debugger::PrintR($data_new_mail);
       // Debugger::testDie();
        if( $data_new_mail && is_object($data_new_mail)){
            $new_user = new User();
            $password = User::authoGenerationPassword();
            $new_user->setPassword($password);
            $new_user->insertNewUser($data_new_mail,$new_user);


            if($new_user->findByUsername($data_new_mail->email)){
//отправка на почту подтверждения о регистрации с логином и паролем
                $data = array(
                    'email' => $data_new_mail->email,
                    'password' => $password
                );
                Yii::$app->mailer->compose(
                        ['html' => 'endRegistration-html', 'text' => 'endRegistration-text'],
                        ['data' => $data]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($data_new_mail->email)
                    ->setSubject('Your NetAssist IPv6 Tunnel Broker registration')
                    ->send();

// удаление записи с адресом электоронной почты из таблици  new_mail
                NewMails::removeData($data_new_mail->email);
                Yii::$app->session->setFlash('success', 'Welcome, '.$data_new_mail->email.' ! Your have registered in
 NetAssist Tunnel Broker. Your password is  '.$password.' Please, log in with
 your e-mail and password..');
            }else{
                Yii::$app->session->setFlash('error', 'User add failed. Please, contact \<a href="mailto:support@netassist.ua">support@netassist.ua\</a>');
            }

        }
        return $this->redirect('/login');
    }
}
