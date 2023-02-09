<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createReview = $auth->createPermission('createReview');
        $createReview->description = 'Написание отзыва';
        $auth->add($createReview);

        $deleteReview = $auth->createPermission('deleteReview');
        $deleteReview->description = 'Редактирование отзыва';
        $auth->add($deleteReview);

        $rule = new \app\rbac\ReviewOwnerRule;
        $auth->add($rule);

        $deleteOwnReview = $auth->createPermission('deleteOwnReview');
        $deleteOwnReview->description = 'Delete own review';
        $deleteOwnReview->ruleName = $rule->name;
        $auth->add($deleteOwnReview);
        $auth->addChild($deleteOwnReview, $deleteReview);

        // добавляем роль "user" и даём роли разрешение создавать и удалять собственные отзывы
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createReview);
        $auth->addChild($user, $deleteOwnReview);
        
        // добавляем роль "admin" и даём роли разрешение "deleteReview"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $deleteReview);
    }
}