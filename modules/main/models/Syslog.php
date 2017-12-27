<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "syslog".
 *
 * @property integer $id
 * @property string $type
 * @property string $msg
 * @property string $created_at
 * @property string $updated_at
 */
class Syslog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syslog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'type', 'msg', 'created_at'], 'required'],
            [['msg'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 7],
            [['is_new'], 'integer'],
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
            'type' => 'Тип',
            'from' => 'Отправитель',
            'to' => 'Получатель',
            'msg' => 'Сообщение',
            'is_new' => 'Новое',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function ViewSysLog($row){
        $rows = Syslog::find()->limit($row)->orderBy(['created_at'=>SORT_DESC])->all();
        $content = '';
        foreach ($rows as $row){
            switch ($row->type) {
                case 'error':
                    $content .= '<tr><td><i class="ace-icon fa fa-bug red"></i></td><td>' . $row->msg . '</td><td>' . $row->created_at . '</td></tr>';
                    break;
                case 'exec':
                    $content .= '<tr><td><i class="ace-icon fa fa-terminal"></i></td><td>' . $row->msg . '</td><td>' . $row->created_at . '</td></tr>';
                    break;
                case 'sms':
                    $content .= '<tr><td><i class="ace-icon fa fa-volume-control-phone green"></i></td><td>' . $row->msg . '</td><td>' . $row->created_at . '</td></tr>';
                    break;
                case 'email':
                    $content .= '<tr><td><i class="ace-icon fa fa-envelope orange"></i></td><td>' . $row->msg . '</td><td>' . $row->created_at . '</td></tr>';
                    break;
            }
        }
        return $content;
    }
}
