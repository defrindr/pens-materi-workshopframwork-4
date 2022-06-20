<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $category_id
 *
 * @property ItemCategory $category
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    public function getUploadedPath()
    {
        $path = Yii::getAlias('@frontend') . '/web/uploads/item/';
        if (file_exists($path) === false) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function getUploadedUrl()
    {
        $path = Yii::$app->urlManagerFrontend->createUrl('/uploads/item/');
        return $path;
    }

    public function getImageUrl()
    {
        $realpath = $this->getUploadedPath() . $this->image;
        $path = $this->getUploadedUrl() . "/" . $this->image;
        if (file_exists($realpath) && $realpath != $this->getUploadedPath()) {
            return $path;
        } else {
            return Yii::$app->urlManagerFrontend->createUrl('/uploads/no-image.jpg');
        }
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date("Y-m-d H:i:s"),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'category_id'], 'required'],
            [['price', 'category_id'], 'integer'],
            [['created_at', 'image'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ItemCategory::className(), ['id' => 'category_id']);
    }
}
