<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\Category;
use app\models\Product;
use app\models\ResetPasswordForm;
use app\models\ResendVerificationEmailForm;
use app\models\Review;
use app\models\VerifyEmailForm;
use yii\base\DynamicModel;
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
        if(!Yii::$app->user->isGuest) {
            $product_query = Product::find()->joinWith('users')
                ->where(['user.id' => Yii::$app->user->identity->getId()]);
            if (!is_null($product_query)) {
                $productPagination = new Pagination([
                    'defaultPageSize' => 5,
                    'totalCount' => $product_query->count(),
                ]);
        
                $products = $product_query->orderBy('title DESC')
                    ->offset($productPagination->offset)
                    ->limit($productPagination->limit)
                    ->all();
            } else {
                $products = [];
                $productPagination = null;
            }
        } else {
            $products = [];
            $productPagination = null;
        }

        $categorySubQuery = Review::find()
            ->select('product_id, COUNT(product_id) as cnt')
            ->groupBy('product_id');

        if (!is_null($categorySubQuery)) {
            $categories = Category::find()
                ->select('category.title, MAX(cnt) as popular')
                ->joinWith('products')
                ->leftJoin(['sub_query' => $categorySubQuery], 'sub_query.product_id = product.id')
                ->groupBy('category.title')
                ->orderBy('popular DESC')
                ->limit(3)
                // ->asArray()
                ->all();
        }

        // $search_query = Product::find()->joinWith('category')
        //     ->andFilterWhere(['like', 'category.title', $this->title])
        //     ->orFilterWhere(['like', 'product.title', $this->title]);
        // $search_pagination = new Pagination([
        //     'defaultPageSize' => 5,
        //     'totalCount' => $search_query->count(),
        // ]);
        
        // $search = $search_query->offset($search_pagination->offset)
        //     ->limit($search_pagination->limit)->all();

        $searchModel = new DynamicModel(['input']);
        $searchModel->addRule(['input'], 'string', ['max' => 128]);
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            $searchProductQuery = Product::find()->where(['like', 'title', $searchModel->input]);
            $searchProductPagination = new Pagination([
                'defaultPageSize' => 5,
                'totalCount' => $searchProductQuery->count(),
            ]);
            $searchProducts = $searchProductQuery->orderBy('title DESC')
                ->offset($searchProductPagination->offset)
                ->limit($searchProductPagination->limit)
                ->all();

            $searchCategoryQuery = Category::find()->where(['like', 'title', $searchModel->input]);
            $searchCategoryPagination = new Pagination([
                'defaultPageSize' => 5,
                'totalCount' => $searchCategoryQuery->count(),
            ]);
            $searchCategories = $searchCategoryQuery->orderBy('title DESC')
                ->offset($searchCategoryPagination->offset)
                ->limit($searchCategoryPagination->limit)
                ->all();
        }
        else {
            $searchProducts = [];
            $searchProductPagination = [];
            $searchCategories = [];
            $searchCategoryPagination = [];
        }

        return $this->render('index', [
            'products' => $products,
            'productPagination' => $productPagination,
            'categories' => $categories,
            'searchModel' => $searchModel,
            'searchProducts' => $searchProducts,
            'searchProductPagination' => $searchProductPagination,
            'searchCategories' => $searchCategories,
            'searchCategoryPagination' => $searchCategoryPagination,
        ]);
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
            Yii::$app->session->setFlash('success', '?????????????? ?????? ???? ??????????????????????. ?????? ??????????, ????????????????????, ?????????????????? ???????? ??????????.');
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
                Yii::$app->session->setFlash('success', '?????????????????? ???????? ?????????? ?????? ???????????????????? ????????????????????.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '?? ??????????????????, ???? ???? ?????????? ???????????????? ???????????? ?????? ???????????????????? ???????????? ?????????????????????? ??????????.');
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
            Yii::$app->session->setFlash('success', '?????????? ???????????? ?????????????? ????????????????????.');

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
                Yii::$app->session->setFlash('success', '?????? ?????????? ?????????????????????? ?????????? ?????? ??????????????????????!');
                $auth = Yii::$app->authManager;
                if (($userId = $user->getId()) == 1) {
                    $role = $auth->getRole('admin');
                } else $role = $auth->getRole('user');
                $auth->assign($role, $user->getId());
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', '????????????????, ???? ???? ?????????? ?????????????????????? ???????? ?????????????? ???????????? ?? ?????????????? ???????????????????????????????? ????????????.');
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
                Yii::$app->session->setFlash('success', '?????????????????? ???????? ?????????????????????? ?????????? ?????? ?????????????????? ???????????????????? ????????????????????.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', '????????????????, ???? ???? ?????????? ???????????????? ?????????????????? ?????????????????????? ???????????? ?? ???????????????????????????? ?????? ???????????????????? ???????????? ?????????????????????? ??????????.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
