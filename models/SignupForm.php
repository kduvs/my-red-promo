<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $surname;
    public $patronymic;

    public function rules()
    {
        return [
            [['email', 'password', 'name', 'surname'], 'required', 'message' => 'Заполните поле'],

            ['patronymic', 'safe'],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Данная почта уже занята.'],

            ['password', 'string', 'min' => 12],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Почта',
            'password' => 'Пароль',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->patronymic = $this->patronymic ?? '';

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        if (!$user->save()) {
            return false;
        }

        return (bool) $this->sendEmail($user);

    }

    private function sendEmail(\app\models\User $user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'noreply'])
            ->setTo($this->email)
            ->setSubject('Подтверждение электронной почты')
            ->send();
    }
}