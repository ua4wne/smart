<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $modelName = $this->tableName();
        parent::afterSave($insert, $changedAttributes);
        if($modelName != 'events'){
            if ($insert) {
                Yii::$app->session->setFlash('success', 'Запись добавлена!');
            } else {
                Yii::$app->session->setFlash('success', 'Запись обновлена!');
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->session->setFlash('success', 'Запись c ID='. $this->id .' была удалена!');
    }

    public function AddEventLog($type,$msg){
            $log = new Events();
            $log->user_id = Yii::$app->user->identity->getId();
            $log->user_ip = $_SERVER['REMOTE_ADDR'];
            $log->type = $type;
            $log->is_read = 0;
            $log->msg = $msg;
            $log->save();
            return true;
    }
}
