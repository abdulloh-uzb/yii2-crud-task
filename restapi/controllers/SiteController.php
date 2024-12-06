<?php

namespace restapi\controllers;

use common\models\User;
use restapi\models\SignupForm;
use restapi\models\LoginForm;
use Yii;
use yii\rest\Controller;

class SiteController extends Controller {

    public function actionRegister()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return ["status" => 'User created successfully!'];
        }
        return $model;
    }

    public function actionLogin(){
        $model = new LoginForm();
        
        if($model->load(Yii::$app->request->post(), '') && ($token = $model->login())){
            return ['token' => $token];
        }
        return $model;    
    }   

    public function actionPasswordResetRequest()
    {
        $email = Yii::$app->request->post('email');
        $user = User::findOne(['email' => $email]);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found.'];
        }

        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        if ($user->save()) {
            // Send email logic here
            return ['status' => 'success', 'message' => 'Password reset token sent to your email.', 'token' => $user->password_reset_token];
        }

        return ['status' => 'error', 'message' => 'Failed to generate reset token.'];
    }

    public function actionResetPassword()
    {
        $token = Yii::$app->request->post('token');
        $password = Yii::$app->request->post('new_password');

        $user = User::findOne(['password_reset_token'=>$token]);

        if(!$user){
            return ['status' => 'error', 'message' => 'Invalid or expired token.'];
        }

        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->password_reset_token = null;
        if ($user->save()) {
            return ['status' => 'success', 'message' => 'Password successfully reset.'];
        }
    
        return ['status' => 'error', 'message' => 'Failed to reset password.'];

    }

}