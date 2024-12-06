<?php
namespace restapi\models;

use yii\base\Model;
use common\models\User;
use Yii;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        
        return $user->save();
    }
}
