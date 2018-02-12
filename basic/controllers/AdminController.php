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
use app\models\Actions;
use app\models\FindUserForm;

use app\components\debugger\Debugger;

class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['admin-panel'],
                'rules' => [
                    [
                        'actions' => ['admin-panel'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
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

    private function getSearchableArray()
    {
        $users_array = User::getUsersList();
        $users_emails_id_val = [];
        $users_ipv4_id_val = [];
        $users_ipv6_id_val = [];
        foreach ($users_array as $k => $v) {
            $users_emails_id_val[$v['id']] = $v['email'];
            $users_ipv4_id_val[$v['id']] = long2ip($v['ip']);
            $users_ipv6_id_val[$v['id']] = SiteController::getIpV6($v['id']);
        }

        return array(
            'emails' => $users_emails_id_val,
            'ipv4' => $users_ipv4_id_val,
            'ipv6' => $users_ipv6_id_val,
        );
    }


    public function actionAdminPanel()
    {

        $modelFindUser = new FindUserForm();
        $searchable_array = $this->getSearchableArray();
        if ($modelFindUser->load(Yii::$app->request->post()) && $modelFindUser->validateData()) {

            $id_action_data = $modelFindUser->getUserIdAction($searchable_array);
            Yii::$app->session->set('search-user-id', $id_action_data['user_id']);
            switch ($id_action_data['action']) {
                case 1:
                    return $this->redirect('/admin-panel/user-view');
                    break;
                case 2:
                    return $this->redirect('admin-panel/user-delete');
                    break;
                case 3:
                    return $this->redirect('admin-panel/user-history');
                    break;
                case 4:
                    return $this->redirect('admin-panel/user-add-admin-rules');
                    break;
                default:
                    return $this->redirect('admin-panel');
                    break;

            }


            //  Yii::$app->session->setFlash('success', 'Your DNS settings are saved.');
            //  if (!Yii::$app->request->isPjax) {
            return $this->render(['/admin-panel']);
            //  }
        }

        return $this->render('admin-panel', [
            'modelFindUser' => $modelFindUser,
            'searchable_array' => $searchable_array,
        ]);
    }

    public function actionUserView()
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
        $user_id = Yii::$app->session->get('search-user-id');
        if(!$user_id){
            Yii::$app->session->setFlash('warning', 'User ID not found.');
            return $this->redirect('/admin-panel');
        }

        $user = new User();
        $user_data = $user->getUserById($user_id);

       // $user_data = User::findUserById($user_id); //Yii::$app->session->get('user_data');



        $user_settings = SiteController::getUserSettings($user_id);

        $modelDns = new DnsForm();

        if ($modelDns->load(Yii::$app->request->post()) && $modelDns->editDns($user_data)) {
            Yii::$app->session->setFlash('success', 'Fore user '.$user_data->email.' DNS settings are saved.');
            //  if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/admin-panel/user-view']);
            //  }
        }

        $modelPtr = new PtrForm();

        if ($modelPtr->load(Yii::$app->request->post()) && $modelPtr->editPtr($user_data)) {
            Yii::$app->session->setFlash('success', 'Fore user '.$user_data->email.' DNS settings are saved.');
            // if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/admin-panel/user-view']);
            //   }
        }

        $modelIpUpdate = new ipUpdateForm();
        if ($modelIpUpdate->load(Yii::$app->request->post()) && $modelIpUpdate->editIp($user_data)) {
            Actions::insertAction($user_data, 5, $modelIpUpdate->ip);
            Yii::$app->session->remove('user_data');
            Yii::$app->session->setFlash('success', 'Fore user '.$user_data->email.' New ipV4 address are saved.');
            //   if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/admin-panel/user-view']);
            // }
        }


        // User::findEmptyId();
        // Debugger::PrintR(User::findEmptyId());
        //Debugger::EhoBr('test');
        //  Debugger::EhoBr(NewMails::removeOldEmails());
        return $this->render('user-view', [
            'user_data' => $user_data,
            'user_settings' => $user_settings,
            'user_id' => $user_data->id,
            'modelDns' => $modelDns,
            'modelPtr' => $modelPtr,
            'modelIpUpdate' => $modelIpUpdate,
        ]);
    }

    public function actionUserDelete()
    {
       // $user_data = $this->getUserData();
        //  $user_id = Yii::$app->user->id;
        //  $user_login = Yii::$app->user->identity->username;

        $user_id = Yii::$app->session->get('search-user-id');
        if(!$user_id){
            Yii::$app->session->setFlash('warning', 'User ID not found.');
            return $this->redirect('/admin-panel');
        }

        $user_data = User::findUserById($user_id);

        if (!$user_data->admin) {

            User::deleteUser($user_data->id);
            Actions::insertAction($user_data, 2);
            Yii::$app->session->setFlash('success', 'Account "' . $user_data->email . '" has been successfully deleted.');
        } else {
            Yii::$app->session->setFlash('warning', 'Unable to remove admin account');
        }

        return $this->redirect('/admin-panel');
    }

    public function actionUserAddAdminRules()
    {
        $user_id = Yii::$app->session->get('search-user-id');
        if(!$user_id){
            Yii::$app->session->setFlash('warning', 'User ID not found.');
            return $this->redirect('/admin-panel');
        }

        $user_data = User::findUserById($user_id);

        if (!$user_data->admin) {

            User::addAdminRules($user_data->id);
            Actions::insertAction($user_data, 11);
            Yii::$app->session->setFlash('success', 'Admins rules fo "' . $user_data->email . '" has been successfully add.');
        } else {
            Yii::$app->session->setFlash('warning', 'Unable to add admin rules');
        }

        return $this->redirect('/admin-panel');


    }



}
