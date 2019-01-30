<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "topic".
 *
 * @property integer $id
 * @property integer $option_id
 * @property string $name
 * @property string $route
 * @property string $payload
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Options $option
 */
class Topic extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'topic_id', 'route'], 'required'],
            [['option_id', 'topic_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['route'], 'string', 'max' => 10],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => MqttData::className(), 'targetAttribute' => ['topic_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option_id' => 'Что контролируем',
            'topic_id' => 'Наименование топика',
            'route' => 'Тип топика',
            'payload' => 'Значение топика',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMqttData()
    {
        return $this->hasOne(MqttData::className(), ['topic_id' => 'id']);
    }
}
