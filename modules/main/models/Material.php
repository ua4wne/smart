<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "material".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property Stock[] $stocks
 */
class Material extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['category_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'category_id' => 'Категория',
            'image' => 'Изображение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['material_id' => 'id']);
    }

    public static function getCategories()
    {
        // Выбираем категории
        $parents = Category::find()
            ->select(['id', 'name'])
            ->distinct(true)
            ->all();
        return ArrayHelper::map($parents, 'id', 'name');
    }

    public function getCategoryName()
    {
        $category = $this->category;
        return $category ? $category->name : '';
    }
}
