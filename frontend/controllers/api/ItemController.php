<?php

namespace frontend\controllers\api;

use yii\rest\ActiveController;

// defrindr

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends ActiveController
{
    public $modelClass = 'common\models\Item';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }
}
