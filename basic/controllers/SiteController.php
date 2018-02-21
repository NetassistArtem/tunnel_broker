<?php

namespace app\controllers;

use app\models\Auth;
use app\models\ipUpdateForm;
use app\models\NewMails;
use app\models\Rdns;
use app\models\DnsForm;
use app\models\PtrForm;
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
use app\models\EmailChangeRequestForm;
use app\models\Actions;

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
                'only' => ['logout', 'main', 'configuration-examples'],
                'rules' => [
                    [
                        'actions' => ['logout', 'main', 'configuration-examples'],
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

    public static function getUserSettings($user_id)
    {
        $e_user_id = Yii::$app->params['euid_index'] + $user_id;

        $ipv6_if_our = sprintf("%s:%x::1", Yii::$app->params['ipv6_if_base'], $user_id);
        $ipv6_if_their = sprintf("%s:%x::2", Yii::$app->params['ipv6_if_base'], $user_id);
        $ipv6_routed = sprintf("%s:%x::/48", Yii::$app->params['ipv6_base'], $e_user_id);

        $r4 = sprintf("%x", $e_user_id & 0xf);
        $r3 = sprintf("%x", ($e_user_id & 0xf0) >> 4);
        $r2 = sprintf("%x", ($e_user_id & 0xf00) >> 8);
        $r1 = sprintf("%x", ($e_user_id & 0xf000) >> 12);
        $rdns = sprintf("%s.%s.%s.%s.0.d.0.0.1.0.a.2.ip6.arpa", $r4, $r3, $r2, $r1);

        $ptr = "no data";
        $ns1 = "no data";
        $ns2 = "no data";
        $data_dns = Rdns::getDNSById($user_id);
        if (!empty($data_dns)) {
            $ptr = $data_dns->ptr;
            $ns1 = $data_dns->dns1;
            $ns2 = $data_dns->dns2;

        }

        $data_array = array(
            'ipv6_if_our' => $ipv6_if_our,
            'ipv6_if_their' => $ipv6_if_their,
            'ipv6_routed' => $ipv6_routed,
            'rdns' => $rdns,
            'ptr' => $ptr,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'server_ipv4_address' => Yii::$app->params['server_ipv4_address'],
            'ipv6_dns_server' => Yii::$app->params['ipv6_dns_server'],
        );

        return $data_array;
    }

    public static function getIpV6($user_id)
    {
        return sprintf("%s:%x::2", Yii::$app->params['ipv6_if_base'], $user_id);
    }

    private function getUserData()
    {
        $session_data = Yii::$app->session->get('user.user_data');
        if ($session_data) {
            return $session_data;
        } else {
            $user_data = User::findUserById(Yii::$app->user->id);
            Yii::$app->session->set('user.user_data', $user_data);
            return $user_data;
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        if(User::isAdmin()){
            return $this->redirect('/admin-panel');
        }else{
            return $this->redirect('/main');
        }

    }


    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user_data = Yii::$app->session->get('user.user_data');
            $auth_data = array(
                'user_id' => $user_data->id,
                'ip_real' => Yii::$app->request->userIP,
                'ip_db' => $user_data->ip,
                'ip_forwarded' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null,
                'email' => $user_data->email,
            );


            Auth::newAuth($auth_data);

            return $this->goHome();
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
        Yii::$app->session->destroy();
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionConfirm()
    {
        $this->getUserData();
        return $this->render('confirm');
    }

    public function actionAdditionalServices()
    {
        $this->getUserData();
        return $this->render('additional-services');
    }

    public function actionAutonomousSystem()
    {
        $this->getUserData();
        return $this->render('autonomous-system');
    }

    public function actionConfigurationExamples()
    {
        $user_data = $this->getUserData();
        $data = $this->getUserSettings($user_data->id);

        return $this->render('configuration-examples', [
            'data' => $data,
            'data_user' => $user_data,

        ]);
    }

    public function actionDynamicIp()
    {
        $this->getUserData();
        $absolute_url = Yii::$app->request->absoluteUrl;
        $base_url_array = explode('/', $absolute_url);
        $base_url = $base_url_array[0] . '://' . $base_url_array[2];


        return $this->render('dynamic-ip', [
            'base_url' => $base_url,
        ]);
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionMain()
    {
        //   $user = User::findUserByPasswordLogin('artemkuchma@gmail.com', '123456789');
        //    Debugger::Br();
        //   Debugger::PrintR($user);
//
        //  Debugger::VarDamp($user);
        //  Debugger::Br();
        //  Debugger::EhoBr(User::isAdmin());
        //    Debugger::VarDamp(User::isAdmin());
        //  Debugger::PrintR();

        // Debugger::PrintR($_SESSION);
        //   Debugger::PrintR($_COOKIE);
        //  Debugger::testDie();
        //   Debugger::PrintR(Yii::$app->session->getCook);

        $user_data = $this->getUserData(); //Yii::$app->session->get('user_data');

        $user_settings = $this->getUserSettings($user_data->id);

        $modelDns = new DnsForm();

        if ($modelDns->load(Yii::$app->request->post()) && $modelDns->editDns($user_data)) {
            Yii::$app->session->setFlash('success', 'Your DNS settings are saved.');
            //  if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/main']);
            //  }
        }

        $modelPtr = new PtrForm();

        if ($modelPtr->load(Yii::$app->request->post()) && $modelPtr->editPtr($user_data)) {
            Yii::$app->session->setFlash('success', 'Your DNS settings are saved.');
            // if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/main']);
            //   }
        }

        $modelIpUpdate = new ipUpdateForm();
        if ($modelIpUpdate->load(Yii::$app->request->post()) && $modelIpUpdate->editIp($user_data)) {
            Actions::insertAction($user_data, 3, $modelIpUpdate->ip);
            Yii::$app->session->remove('user.user_data');
            Yii::$app->session->setFlash('success', 'Your new ipV4 address are saved.');
            //   if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/main']);
            // }
        }

        $modelEmailChangeRequest = new EmailChangeRequestForm();

        if ($modelEmailChangeRequest->load(Yii::$app->request->post()) && $modelEmailChangeRequest->validate()) {
            if ($modelEmailChangeRequest->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->redirect('/main');
            } else {
                Yii::$app->session->setFlash('warning', 'Sorry, we are unable to change e-mail.');
            }
        }


        // User::findEmptyId();
        // Debugger::PrintR(User::findEmptyId());
        //Debugger::EhoBr('test');
        //  Debugger::EhoBr(NewMails::removeOldEmails());
        return $this->render('main', [
            'user_data' => $user_data,
            'user_settings' => $user_settings,
            'user_id' => $user_data->id,
            'modelDns' => $modelDns,
            'modelPtr' => $modelPtr,
            'modelIpUpdate' => $modelIpUpdate,
            'modelEmailChangeRequest' => $modelEmailChangeRequest,
        ]);
    }

    public function actionChangeEmail($token)
    {
        $data_new_mail = NewMails::findByRegistrationToken($token);
        // Debugger::PrintR($data_new_mail);
        // Debugger::testDie();
        $user_id = Yii::$app->user->id;
        $user = new User();
        $user = $user->getUserById($user_id);
        if ($data_new_mail && is_object($data_new_mail)) {
            User::updateEmail($data_new_mail->email, Yii::$app->user->id);


                Actions::insertAction($user, 4, 0, $data_new_mail->email );

            Yii::$app->session->setFlash('success', 'Your e-mail is changed.');
// удаление записи с адресом электоронной почты из таблици  new_mail
                NewMails::removeData($data_new_mail->email);

            $user_data = User::findUserById(Yii::$app->user->id);
            Yii::$app->session->set('user.user_data', $user_data);
            return $this->redirect('/main');



        }else{

            Yii::$app->session->setFlash('warning', 'Change e-mail token is not valid.');
            return $this->redirect('/main');
        }



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
                return $this->redirect('/confirm');
            } else {
                Yii::$app->session->setFlash('warning', 'Sorry, we are unable to reset password for email provided.');
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
        NewMails::removeOldEmails();

        if ($model_registration_request->load(Yii::$app->request->post()) && $model_registration_request->validate()) {
            if ($model_registration_request->sendEmail()) {
                Actions::insertAction($model_registration_request, 10);
                Yii::$app->session->setFlash('success', 'We just sent you the e-mail with the link to complete registration process. Please read that e-mail and click on the link.');
                return $this->redirect('/confirm');
            } else {
                Yii::$app->session->setFlash('warning', 'Sorry, we are unable registration you. Try again later.');
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
        if ($data_new_mail && is_object($data_new_mail)) {
            $new_user = new User();
            $password = User::authoGenerationPassword();
            $new_user->setPassword($password);
            $new_user->insertNewUser($data_new_mail, $new_user);
            $user = $new_user->findByUsername($data_new_mail->email);


            if ($user) {
                Actions::insertAction($user, 1);

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
                Yii::$app->session->setFlash('success', 'Welcome, ' . $data_new_mail->email . ' ! Your have registered in
 NetAssist Tunnel Broker. Your password is  ' . $password . ' Please, log in with
 your e-mail and password..');
            } else {
                Yii::$app->session->setFlash('warning', 'User add failed. Please, contact \<a href="mailto:support@netassist.ua">support@netassist.ua\</a>');
            }

        }
        return $this->redirect('/login');
    }

    public function actionDeleteUser()
    {
        $user_data = $this->getUserData();
      //  $user_id = Yii::$app->user->id;
      //  $user_login = Yii::$app->user->identity->username;

        if(!$user_data->admin){
            Yii::$app->user->logout();
            User::deleteUser($user_data->id);
            Actions::insertAction($user_data, 2);
            Yii::$app->session->setFlash('success', 'Your account "' . $user_data->email . '" has been successfully deleted.');
        }else{
            Yii::$app->session->setFlash('warning','Unable to remove admin account');
        }

        return $this->redirect('/');
    }

    public function actionAutochangeIp()
    {

        $login = Yii::$app->request->get('l');
        $password = Yii::$app->request->get('p');
        $ip = Yii::$app->request->get('ip');
        if ($login && $password && $ip) {
            //   $user = User::findUserByPasswordLogin($login,$password);
            $login_form = new LoginForm();
            $login_form->username = $login;
            $login_form->password = $password;
            $user = $login_form->getUser();
            $user->validatePassword($login_form->password);

            if ($user) {
                if ($user->validatePassword($login_form->password)) {


                    if (ipUpdateForm::ipValidationMain($ip)) {
                        if (ipUpdateForm::uniqueIPMain($ip)) {


                            if (User::updateUserIp($user->id, $ip) === null) {
                                $user_data = $this->getUserData();
                                Actions::insertAction($user_data, 5, $ip);
                                Yii::$app->session->remove('user_data');
                                Yii::$app->session->setFlash('success', 'The IP will be updated in 60 seconds.');
                            } else {
                                Yii::$app->session->setFlash('warning', 'IP address is not updated.');
                            }
                        } else {
                            Yii::$app->session->setFlash('warning', 'IPv4 address ' . $ip . ' is already registered in our system for another customer!');
                        }

                        // return $this->redirect('/');
                    } else {
                        Yii::$app->session->setFlash('warning', 'Incorrect IPv4 address.');
                    }
                } else {
                    Yii::$app->session->setFlash('warning', 'The user with login "' . $login . '" and password "' . $password . '" is not exist!');
                }

            } else {
                Yii::$app->session->setFlash('warning', 'The user with login "' . $login . '" and password "' . $password . '" is not exist!');
                //return $this->redirect('/');
            }

        } else {
            Yii::$app->session->setFlash('warning', 'You must enter three parameters: login, password, IP address.');
            // return $this->redirect('/');
        }
        return $this->redirect('/');
    }


}
