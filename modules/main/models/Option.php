<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "option".
 *
 * @property integer $id
 * @property integer $device_id
 * @property double $val
 * @property string $unit
 * @property string $alias
 * @property string $name
 * @property integer $to_log
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 */
class Option extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'val', 'alias', 'name'], 'required'],
            [['device_id', 'to_log'], 'integer'],
            [['val'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['unit'], 'string', 'max' => 7],
            [['alias', 'name'], 'string', 'max' => 50],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'val' => 'Значение параметра',
            'unit' => 'Ед измерения',
            'alias' => 'Псевдоним',
            'name' => 'Наименование',
            'to_log' => 'Писать в лог',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }
}
