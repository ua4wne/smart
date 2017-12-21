<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property integer $option_id
 * @property string $condition
 * @property double $val
 * @property string $action
 * @property string $text
 * @property string $runtime
 * @property integer $step
 * @property integer $expire
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Options $option
 */
class Rule extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'condition', 'val', 'action', 'text', 'step'], 'required'],
            [['option_id', 'step'], 'integer'],
            [['val'], 'number'],
            [['text'], 'string'],
            [['runtime', 'created_at', 'updated_at'], 'safe'],
            [['condition', 'action'], 'string', 'max' => 5],
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
            'condition' => 'Условие на значение',
            'val' => 'Значение параметра',
            'action' => 'Действие',
            'text' => 'Текст сообщения или команда',
            'runtime' => 'Время запуска',
            'step' => 'Период запуска, сек.',
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
