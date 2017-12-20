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
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Option $option
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
            [['option_id', 'condition', 'val', 'action', 'text'], 'required'],
            [['option_id'], 'integer'],
            [['val'], 'number'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'option_id' => 'Контролируемый параметр',
            'condition' => 'Условие на значение параметра',
            'val' => 'Значение параметра',
            'action' => 'Действие',
            'text' => 'Текст сообщения или команда',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления
            ',
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
