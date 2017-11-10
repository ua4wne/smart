<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;
use app\modules\main\models\Device;
use app\modules\main\models\DeviceType;

/**
 * This is the model class for table "counter_log".
 *
 * @property integer $id
 * @property integer $device_id
 * @property string $_year
 * @property string $_month
 * @property double $val
 * @property double $koeff
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 */
class CounterLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', '_year', '_month', 'val'], 'required'],
            [['device_id'], 'integer'],
            [['val', 'delta', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['_year'], 'string', 'max' => 4],
            [['_month'], 'string', 'max' => 2],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Счетчик',
            '_year' => 'Год',
            '_month' => 'Месяц',
            'val' => 'Показания счетчика',
            'delta' => 'Потребление',
            'price' => 'Стоимость',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    //таблица по счетчикам
    public static function StatCounter($year){
        $data = array(1=>0,0,0,0,0,0,0,0,0,0,0,0); //показания счетчиков, нумерация с 1
        $content='<table class="table table-hover table-striped">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';
        $type = DeviceType::findOne(['name'=>'Счетчик'])->id;
        $models = Device::find()->select(['id','name'])->where(['=','type_id',$type])->all();
        foreach ($models as $model){
            $content.='<tr><td>'.$model->name.'</td>';
            $logs = CounterLog::find()->select(['_month','delta'])->where(['=','device_id',$model->id])->andWhere(['=','_year',$year])->orderBy('_month', SORT_ASC)->all();
            //return print_r($logs);
            $k=1;
            foreach($logs as $log){
                if((int)$log->_month == $k){
                    $content .='<td>'.$log->delta.'</td>';
                }
                else
                    $content .='<td>0</td>';
                $k++;
            }
            while($k<13){
                $content .='<td>0</td>';
                $k++;
            }
            $content .='</tr>';
        }
        $content.='</tr></table>';
        return $content;
    }

}
