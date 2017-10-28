<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "stock".
 *
 * @property integer $id
 * @property integer $cell_id
 * @property integer $material_id
 * @property integer $quantity
 * @property integer $unit_id
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Cell $cell
 * @property Material $material
 * @property Unit $unit
 */
class Stock extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cell_id', 'material_id', 'quantity', 'unit_id', 'price'], 'required'],
            [['cell_id', 'material_id', 'quantity', 'unit_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['cell_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cell::className(), 'targetAttribute' => ['cell_id' => 'id']],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Material::className(), 'targetAttribute' => ['material_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cell_id' => 'Ячейка',
            'material_id' => 'Номенклатура',
            'cell.name' => 'Ячейка',
            'material.name' => 'Номенклатура',
            'quantity' => 'Количество',
            'unit_id' => 'Ед. измерения',
            'price' => 'Цена, руб.',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCell()
    {
        return $this->hasOne(Cell::className(), ['id' => 'cell_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::className(), ['id' => 'material_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    public static function getCells()
    {
        // Выбираем ячейки
        $parents = Cell::find()
            ->select(['id', 'name'])
            ->distinct(true)
            ->all();
        return ArrayHelper::map($parents, 'id', 'name');
    }

    public function getCellName()
    {
        $cell = $this->cell;
        return $cell ? $cell->name : '';
    }

    public static function getUnits()
    {
        // Выбираем единицы
        $parents = Unit::find()
            ->select(['id', 'name'])
            ->distinct(true)
            ->all();
        return ArrayHelper::map($parents, 'id', 'name');
    }

    public function getUnitName()
    {
        $unit = $this->unit;
        return $unit ? $unit->name : '';
    }
}
