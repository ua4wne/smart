<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "mqtt_data".
 *
 * @property integer $id
 * @property string $time
 * @property string $topic
 * @property string $value
 *
 * @property Topic[] $topics
 */
class MqttData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mqtt_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'topic'], 'required'],
            [['time'], 'safe'],
            [['topic', 'value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Время',
            'topic' => 'Топик',
            'value' => 'Значение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::className(), ['topic_id' => 'id']);
    }
}
