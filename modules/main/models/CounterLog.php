<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "counter_log".
 *
 * @property integer $id
 * @property integer $device_id
 * @property string $_year
 * @property string $_month
 * @property double $val
 * @property double $koeff
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 */
class CounterLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', '_year', '_month', 'val', 'koeff', 'price'], 'required'],
            [['device_id'], 'integer'],
            [['val', 'koeff', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['_year'], 'string', 'max' => 4],
            [['_month'], 'string', 'max' => 2],
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
            '_year' => 'Год',
            '_month' => 'Месяц',
            'val' => 'Показания',
            'koeff' => 'Тариф',
            'price' => 'Цена',
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
