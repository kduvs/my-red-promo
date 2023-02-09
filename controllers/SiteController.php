<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\Category;
use app\models\ResetPasswordForm;
use app\models\ResendVerificationEmailForm;
use app\models\Review;
use app\models\VerifyEmailForm;
use PhpParser\Node\Expr\Isset_;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
        // $productProvider = new ActiveDataProvider([
        //     'query' => Product::find()
        //         ->joinWith('user')
        //         ->where(['user.id' => Yii::$app->user->identity->getId()])->limit(5),
        //     'pagination' => [
        //         'pageSize' => 5,
        //     ],
        // ]);
        // $products = ListView::widget([
        //     'dataProvider' => $productProvider,
        //     'itemView' => '_product',
        // ]);
        
        // // $doubleSubQuery = Category::find()
        // //     ->select('id, COUNT(product_id) as cnt')
        // //     ->leftJoin(['sub_query' => $categorySubQuery], 'sub_query.product_id = product_id')
        // //     ->groupBy('id')
        // //     ->orderBy('cnt DESC');
        // // $categoryQuery = Category::find()->where(['in', 'id', $doubleSubQuery])

        // $categorySubQuery = Review::find()
        //     ->select('product_id, COUNT(product_id) as cnt')
        //     ->groupBy('product_id');
        
        // $categoryQuery = Category::find()
        //     ->select('id, MAX(cnt) as popular')
        //     ->leftJoin(['sub_query' => $categorySubQuery], 'sub_query.product_id = category.product_id')
        //     ->groupBy('id')
        //     ->orderBy('popular DESC');

        // $categoryProvider = new ActiveDataProvider([
        //     'query' => $categoryQuery->limit(5),
        //     'pagination' => [
        //         'pageSize' => 5,
        //     ],
        // ]);

        // $categories = ListView::widget([
        //     'dataProvider' => $categoryProvider,
        //     'itemView' => '_category',
        // ]);
        
        // return $this->render('index', [
        //     'products' => $products,
        //     'categories' => $categories,
        // ]);
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
            return $this->goBack();
        }

        $model->password = '';
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
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signup action.
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Спасибо вам за регистрацию. Для входа, пожалуйста, проверьте вашу почту.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * RequestPasswordReset action.
     *
     * @return Response|string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте вашу почту для дальнейшей инструкции.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль для указанного адреса электронной почты.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * ResetPassword action.
     *
     * @return Response|string
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль успешно установлен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * VerifyEmail action.
     *
     * @return Response|string
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Ваш адрес электронной почты был подтвержден!');
                $auth = Yii::$app->authManager;
                if (($userId = $user->getId()) == 1) {
                    $role = $auth->getRole('admin');
                } else $role = $auth->getRole('user');
                $auth->assign($role, $user->getId());
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Извините, мы не можем подтвердить вашу учетную запись с помощью предоставленного токена.');
        return $this->goHome();
    }

    /**
     * ResendVerificationEmail action.
     *
     * @return Response|string
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Извините, мы не можем повторно отправить электронное письмо с подтверждением для указанного адреса электронной почты.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
