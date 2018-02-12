<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\debugger\Debugger;
use app\models\User;

AppAsset::register($this);
$flash = Yii::$app->session->getAllFlashes();
$guest = Yii::$app->user->isGuest;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/netAssist3.png', ['alt' => Yii::$app->name]),
        'brandUrl' => '/main',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top custom-style-navbar',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
          //  User::isAdmin() ? (
                [
                    'label' => 'Admin panel',
                    'url' => ['/admin/admin-panel'],
                    'visible' => User::isAdmin(),
                    'linkOptions' => ['class' => 'admin-panel']
                ],

           // ) : ( ''

           // ),


            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout ',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),
            ['label' => 'Information', 'url' => '/',
                 'active' => (
                  Yii::$app->request->url == "/dynamic-ip" ||
                  Yii::$app->request->url == "/autonomous-system" ||
                 Yii::$app->request->url == "/additional-services"
                   ),
                'items' => [
                    ['label' => 'Dynamic IP', 'url' => '/dynamic-ip',],
                    ['label' => 'Autonomous System', 'url' => "/autonomous-system",],
                    ['label' => 'Additional services', 'url' => '/additional-services',],
                    $guest ? ('') : (['label' => 'Configuration Examples', 'url' => ['/configuration-examples']]),

                ]
            ],


        ],

    ]);
    NavBar::end();

    $this->registerJsFile(
        'scripts/tb.js',
        ['depends' => 'app\assets\AppAsset']
    );

    ?>

    <div class="container">

        <div>
            <?php
            if (!empty($flash)):
                foreach ($flash as $k => $v):?>
                    <div class="alert alert-<?= $k ?> alert-dismissible  show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="alert-heading"><?= $k ?>!</h4>

                        <p><?= $v ?></p>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
        <div class="row content-position">
            <?= $content ?>
        </div>




        <div class="row">
            <div class="col-sm-5 col-md-5 col-lg-5">
                <div class="panel panel-default custom-panel-style footer-block-height">
                    <div class="panel-heading">
                        <h3>Contacts</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            Support:
                        </p>
                        <ul>
                            <li><a href="http://conference.netassist.ua/index.php?t=index&cat=6">Forum</a></li>
                            <li><a href="mailto:support@netassist.ua">E-mail: support@netassist.ua</a></li>
                            <li>
                                <p>24h duty emergency phones:</p>
                                <p>+380(44)239-89-99</p>

                            </li>
                        </ul>


                    </div>

                </div>
            </div>
            <div class="col-sm-7 col-md-7 col-lg-7">
                <div class="panel panel-default custom-panel-style footer-block-height">
                    <div class="panel-heading">
                        <h3>Donate</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            We provide our service free of charge. If you like it and found it useful, you can help us
                            to maintain and improve it. Please, donate this project via PayPal or WebMoney:
                        </p>
                        <div class="col-sm-6 col-md-6 col-lg-6 donate-style">
                            <a class="btn btn-primary btn-custom" href="#" role="button">Donate by PayPal </a>
                            <img class="donate-image" src="/basic/web/images/paypal5.png" alt="">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 donate-style">
                            <a class="btn btn-primary btn-custom" href="#" role="button">Donate by WebMony </a>
                            <img class="donate-image" src="/basic/web/images/webmany5.png" alt="">
                        </div>


                    </div>

                </div>
            </div>

        </div>


    </div>
</div>

<footer class="footer footer-custom">
    <div class="container">
        <p class="pull-left">&copy; NetAssist <?= date('Y') ?></p>


    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
