<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "tarif".
 *
 * @property integer $id
 * @property integer $device_id
 * @property double $koeff
 * @property string $unit
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 */
class Tarif extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'koeff', 'unit'], 'required'],
            [['device_id'], 'integer'],
            [['koeff'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['unit'], 'string', 'max' => 5],
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
            'koeff' => 'Тариф',
            'unit' => 'Ед. изм.',
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
