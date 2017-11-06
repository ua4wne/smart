<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $uid
 * @property string $name
 * @property string $descr
 * @property string $address
 * @property integer $verify
 * @property integer $protocol_id
 * @property integer $location_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Location $location
 */
class Device extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'location_id'], 'required'],
            [['descr'], 'string'],
            [['verify', 'protocol_id', 'location_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uid'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 70],
            [['image'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 32],
            [['uid'], 'unique'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Идентификатор',
            'name' => 'Наименование',
            'image' => 'Изображение',
            'descr' => 'Описание',
            'address' => 'Адрес',
            'verify' => 'Контроль',
            'protocol_id' => 'Протокол',
            'location_id' => 'Локация',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }
}
