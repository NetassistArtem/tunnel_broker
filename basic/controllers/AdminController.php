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
use app\controllers\SiteController;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

use app\models\Actions;
use app\models\FindUserForm;
use app\models\HistoryForm;
use yii\data\Pagination;
use app\models\MigrationUser;

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
                'only' => ['admin-panel', 'user-history', 'user-view', 'user-delete', 'add-admin-rules', 'migration-users'],
                'rules' => [
                    [
                        'actions' => ['admin-panel', 'user-history', 'user-view', 'user-delete', 'add-admin-rules', 'migration-users'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ],
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

        ];
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

    private function getUserDataBySession()
    {
        $user_id = Yii::$app->session->get('search-user-id');
        if (!$user_id) {
            Yii::$app->session->setFlash('warning', 'User ID not found.');

            return null;

            // $this->redirect('/admin-panel');
        }

        $user = new User();
        return $user_data = $user->getUserById($user_id);
    }

    private function parsingData($data_array)
    {
        $new_data = [];
        $actions_name_array = Yii::$app->params['actions'];

        foreach($data_array as $k => $v){
           // дописать проверку н совпадение или попробовть uksort()
                $new_data[$v['created_at']] = array(
                    'id' => $v['id'],
                    'user_id' => $v['user_id'],
                    'ip_db' => long2ip($v['ip_db']),
                    'ip_real'=> long2ip($v['ip_real']),
                    'ip_db_new' => isset($v['ip_db_new'])? long2ip($v['ip_db_new']) : '',
                    'email' => $v['email'],
                    'email_new' => isset($v['email_new']) ? $v['email_new'] : '',
                    'created_at' => Yii::$app->formatter->asDatetime($v['created_at'],'yyyy-MM-dd HH:mm:ss'),
                    'action' => isset($v['action']) ? $actions_name_array[$v['action']] :$actions_name_array[12],
                    'by_admin' => isset($v['by_admin'])  ? 'Yes' : '',
                );



        }

        krsort($new_data);
        return $new_data;
    }


    public function actionAdminPanel()
    {



        $this->getUserData();
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
            //  return $this->render(['/admin-panel']);
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

        $user_data = $this->getUserDataBySession();
        if (!$user_data) {
            return $this->redirect('/admin-panel');
        }


        // $user_data = User::findUserById($user_id); //Yii::$app->session->get('user_data');


        $user_settings = SiteController::getUserSettings($user_data->id);

        $modelDns = new DnsForm();

        if ($modelDns->load(Yii::$app->request->post()) && $modelDns->editDns($user_data)) {
            Yii::$app->session->setFlash('success', 'Fore user ' . $user_data->email . ' DNS settings are saved.');
            //  if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/admin-panel/user-view']);
            //  }
        }

        $modelPtr = new PtrForm();

        if ($modelPtr->load(Yii::$app->request->post()) && $modelPtr->editPtr($user_data)) {
            Yii::$app->session->setFlash('success', 'Fore user ' . $user_data->email . ' DNS settings are saved.');
            // if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/admin-panel/user-view']);
            //   }
        }

        $modelIpUpdate = new ipUpdateForm();
        if ($modelIpUpdate->load(Yii::$app->request->post()) && $modelIpUpdate->editIp($user_data)) {
            Actions::insertAction($user_data, 5, $modelIpUpdate->ip);
            Yii::$app->session->remove('user_data');
            Yii::$app->session->setFlash('success', 'Fore user ' . $user_data->email . ' New ipV4 address are saved.');
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

        $user_data = $this->getUserDataBySession();
        if (!$user_data) {
            return $this->redirect('/admin-panel');
        }

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


        $user_data = $this->getUserDataBySession();
        if (!$user_data) {
            return $this->redirect('/admin-panel');
        }

        if (!$user_data->admin) {

            User::addAdminRules($user_data->id);
            Actions::insertAction($user_data, 11);
            Yii::$app->session->setFlash('success', 'Admins rules fo "' . $user_data->email . '" has been successfully add.');
        } else {
            Yii::$app->session->setFlash('warning', 'Unable to add admin rules');
        }

        return $this->redirect('/admin-panel');


    }

    public function actionUserHistory()
    {
        $user_data = $this->getUserDataBySession();

        if (!$user_data) {
            return $this->redirect('/admin-panel');
        }

        $modelHistory = new HistoryForm();
        $actions_array = Yii::$app->params['actions'];
        $search_user_email = $user_data->email;
        $time_to = time() + 86399;
        $time_from = $time_to - Yii::$app->params['default_time_interval'];
        $action = 13; // all
        $selected_all = null;
        //данные для работы пагинации
        $request_data = Yii::$app->session->get('request-history-param');
        if($request_data){
            if(Yii::$app->request->get('page')){
                $search_user_email = $request_data['search_user_email'];
                $time_to = $request_data['time_to'];
                $time_from = $request_data['time_from'];
                $action = $request_data['action'];
                $selected_all = $request_data['selected_all'];
            }

        }




        if ($modelHistory->load(Yii::$app->request->post()) && $modelHistory->history($user_data->email)) {
            //  Actions::insertAction($user_data, 5, $modelIpUpdate->ip);
            //Yii::$app->session->remove('user_data');
            //  Yii::$app->session->setFlash('success', 'Fore user '.$user_data->email.' New ipV4 address are saved.');
            //   if (!Yii::$app->request->isPjax) {
            //    return $this->redirect(['/admin-panel/user-view']);
            // }
//Debugger::EhoBr($modelHistory->all_users);
            //   Debugger::EhoBr();
            //  Debugger::EhoBr(Yii::$app->request->post('HistoryForm')['all_users'][1]);
          //  Debugger::PrintR($_POST);


            if (isset(Yii::$app->request->post('HistoryForm')['all_users'][0])) {//костыльный вызов свойства - вызов через обхъект по не понятной причине не рботает - всегд пустое значение
                //   Debugger::EhoBr('test');
                Yii::$app->session->remove('request-history-param');
                $search_user_email = null;
                $selected_all = 1;
            }
            $time_from = Yii::$app->formatter->asTimestamp($modelHistory->time_from);
            $time_to = Yii::$app->formatter->asTimestamp($modelHistory->time_to);
            $action = $modelHistory->action;

            $session_array = array(
                'search_user_email' => $search_user_email,
                'time_to' => $time_to,
                'time_from' => $time_from,
                'action' => $modelHistory->action,
                'selected_all'=> $selected_all,
            );

            Yii::$app->session->set('request-history-param', $session_array);
        }



        //   Debugger::EhoBr($time_from);
        //   Debugger::EhoBr($time_to);
        //   Debugger::EhoBr($action);
        //   Debugger::EhoBr($search_user_email);
        $h_data = Actions::getUserHistory($search_user_email, $action, $time_from, $time_to);
        $history_data =  $this->parsingData($h_data);
       // Debugger::PrintR($data);
        // $actions = self::find()->where($param_array)->andWhere(['between', 'created_at', $time_from, $time_to])->asArray()->all();
        $actions_name_array = Yii::$app->params['actions'];

        $history_per_page = Yii::$app->params['history_per_page'];
        $pages = new Pagination(['totalCount' => count($history_data), 'pageSize' => $history_per_page]);
        $pages->pageSizeParam = false;

        $history_data_page = array_slice($history_data, $pages->offset, $pages->limit, $preserve_keys = true);



        return $this->render('user-history', [
            'user_data' => $user_data,
            'modelHistory' => $modelHistory,
            'actions_array' => $actions_array,
            'action' => $action,
            'time_to' => $time_to,
            'time_from' => $time_from,
            'selected_all' => $selected_all,
            'history_data' => $history_data,
            'actions_name_array' => $actions_name_array,
            'pages' => $pages,
            'history_data_page' => $history_data_page,


        ]);

    }

    public function actionMigrationUsers()
    {
        $modelMigrationUser = new MigrationUser;
//параметр 1 (выборка от (1-1)*5000 до 1*5000, если 2 то (2-1)*5000 до 2*5000)
        $old_users_data = $modelMigrationUser->getUsersList(5);
        $not_data = null;
        if(empty($old_users_data)){
            $not_data = 'Нет данных';
        }
     //   Debugger::PrintR($old_users_data);

        return $this->render('migration-users',[
            'not_data' => $not_data
        ]);
    }


}
