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
            [['option_id', 'name', 'route', 'payload'], 'required'],
            [['option_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['route'], 'string', 'max' => 10],
            [['payload'], 'string', 'max' => 70],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_id' => 'id']],
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
            'name' => 'Наименование топика',
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
}
