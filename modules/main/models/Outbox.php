<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "outbox".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $msg
 * @property integer $is_new
 * @property string $created_at
 * @property string $updated_at
 */
class Outbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'msg', 'created_at'], 'required'],
            [['msg'], 'string'],
            [['is_new'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['from', 'to'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'Отправитель',
            'to' => 'Получатель',
            'msg' => 'Сообщение',
            'is_new' => 'Новое',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
}
