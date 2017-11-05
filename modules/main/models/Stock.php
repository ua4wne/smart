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

    public function getImage()
    {
        $img =  $this->material;
        return $img ? $img->image : '';
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

    public static function ViewStock(){
        $query =  'SELECT s.material_id AS id, c.name AS cell, m.name AS material, cat.name AS category, s.quantity AS quantity, u.name AS unit, s.price AS price ' .
            'FROM stock s ' .
            'INNER JOIN cell c ON (c.id = s.cell_id) ' .
            'INNER JOIN material m ON (m.id = s.material_id) '.
            'INNER JOIN category cat ON (cat.id = m.category_id) '.
            'INNER JOIN unit u ON (u.id = s.unit_id) ';
        // подключение к базе данных
        $connection = \Yii::$app->db;
        // Составляем SQL запрос
        $model = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $rows = $model->queryAll();
        $content = '<table class="table table-striped table-bordered table-hover" id="dataTables-stock">
                        <thead><tr><th>ID</th><th>Ячейка</th><th>Номенклатура</th><th>Категория</th><th>Кол-во</th><th>Ед. изм</th><th>Цена</th><th>Действия</th></tr></thead>
                            <tbody>';
        foreach ($rows as $row){
            $content.='<tr><td>'.$row['id'].'</td><td>'.$row['cell'].'</td><td>'.$row['material'].'</td>
                        <td>'.$row['category'].'</td><td>'.$row['quantity'].'</td><td>'.$row['unit'].'</td><td>'.$row['price'].'</td>
                        <td><a href="/main/stock/store/view?id='.$row['id'].'" title="Просмотр" aria-label="Просмотр"><span class="glyphicon glyphicon-eye-open"></span></a>
                         <a href="/main/stock/store/update?id='.$row['id'].'" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
                         <a href="/main/stock/store/delete?id='.$row['id'].'" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></tr>';
        }
        $content.='</tbody></table>';
        return $content;
    }
}
