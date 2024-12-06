<?php

namespace restapi\controllers;

use yii\rest\Controller;
use yii\web\Response;
use common\models\Post;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

// use yii\rest\ActiveController;

class PostController extends Controller
{

    public $modelClass = "common\models\Post";

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        return $behaviors;
    }

    /**
     * Read (GET): Barcha postlarni qaytaradi
     */
    public function actionIndex()
    {
        return Post::find()->all();
    }

    /**
     * Read (GET): Bitta postni qaytaradi
     * @param int $id
     */
    public function actionView($id)
    {
        $post = Post::findOne($id);
        if ($post) {
            return $post;
        }
        return ['status' => 'error', 'message' => 'Post not found'];
    }

    /**
     * Create (POST): Yangi post yaratadi
     */
    public function actionCreate()
    {
        $model = new Post();
        $data = Yii::$app->request->post();
        $data['owner_id'] = Yii::$app->user->id;
        if ($model->load($data, '') && $model->save()) {
            return [
                'status' => 'success',
                'data' => $model,
            ];
        }

        return [
            'status' => 'error',
            'errors' => $model->errors,
        ];
    }

    /**
     * Update (PUT): Mavjud postni yangilaydi
     * @param int $id
     */
    public function actionUpdate($id)
    {
        $model = Post::findOne($id);

        if (!$model) {
            return ['status' => 'error', 'message' => 'Post not found'];
        }

        if (!$this->isOwnerOrAdmin($model)) {
            return ['status' => 'error', 'message' => 'You dont have permission.'];
        }


        $data = Yii::$app->request->bodyParams; // PUT orqali yuborilgan ma'lumotlarni olish
        if ($model->load($data, '') && $model->save()) {
            return [
                'status' => 'success',
                'data' => $model,
            ];
        }

        return [
            'status' => 'error',
            'errors' => $model->errors,
        ];
    }

    /**
     * Delete (DELETE): Postni o'chiradi
     * @param int $id
     */
    public function actionDelete($id)
    {
        $model = Post::findOne($id);

        if (!$model) {
            return ['status' => 'error', 'message' => 'Post not found'];
        }

        if (!$this->isOwnerOrAdmin($model)) {
            return ['status' => 'error', 'message' => 'You dont have permission.'];
        }


        if ($model->delete()) {
            return ['status' => 'success', 'message' => 'Post deleted'];
        }

        return ['status' => 'error', 'message' => 'Failed to delete post'];
    }

    private function isOwnerOrAdmin($model)
    {
        $user = Yii::$app->user->identity;
        return $user->role == "admin" || $model->owner_id == $user->id;
    }
}
