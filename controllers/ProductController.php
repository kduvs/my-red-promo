<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductSearch;
use app\models\Review;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use Yii;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->identity->isAdmin) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('createReview')) {
            $review = new Review();
            if ($this->request->isPost) {
                if ($review->load($this->request->post())) {
                    $review->user_id = Yii::$app->user->identity->id;
                    $review->product_id = $id;
                    $review->save();
                    $review = new Review();
                }
            }
        } else $review = null;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'review' => $review,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->identity->isAdmin) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $model = new Product();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $uploadForm = new UploadForm();
                $uploadForm->title = $model->title;
                $uploadForm->image = UploadedFile::getInstance($model, 'image');
                if ($uploadForm->upload()) {
                    $model->image = $uploadForm->path;
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->identity->isAdmin) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $uploadForm = new UploadForm();
            $uploadForm->title = $model->title;
            $uploadForm->image = UploadedFile::getInstance($model, 'image');
            if ($uploadForm->upload()) {
                $model->image = $uploadForm->path;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->identity->isAdmin) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
