<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "logger".
 *
 * @property integer $id
 * @property integer $option_id
 * @property double $val
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Options $option
 */
class Logger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'val'], 'required'],
            [['option_id'], 'integer'],
            [['val'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'option_id' => 'Параметр',
            'val' => 'Значение',
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
