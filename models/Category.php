<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 *
 * @property ProductCategory[] $productCategories
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    public $popular;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 32],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    // /**
    //  * Gets query for [[ProductCategories]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getProductCategories()
    // {
    //     return $this->hasMany(ProductCategory::class, ['category_id' => 'id']);
    // }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('product_category', ['category_id' => 'id']);
    }
}
