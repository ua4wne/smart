<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "config".
 *
 * @property integer $id
 * @property string $param
 * @property string $val
 * @property string $descr
 * @property string $created_at
 * @property string $updated_at
 */
class Config extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param', 'val'], 'required'],
            [['descr'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['param'], 'string', 'max' => 50],
            [['val'], 'string', 'max' => 100],
            [['param'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param' => 'Параметр',
            'val' => 'Значение',
            'descr' => 'Описание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
