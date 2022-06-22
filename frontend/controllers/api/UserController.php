<?php

namespace frontend\controllers\api;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public function behaviors()
    {
        $parent = parent::behaviors();
        return $parent;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        return $verbs;
    }
}
