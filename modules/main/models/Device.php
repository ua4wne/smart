<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $uid
 * @property string $name
 * @property string $descr
 * @property string $address
 * @property integer $verify
 * @property integer $protocol_id
 * @property integer $location_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Location $location
 */
class Device extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'location_id', 'type_id'], 'required'],
            [['descr'], 'string'],
            [['verify', 'protocol_id', 'location_id', 'type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uid'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 70],
            [['image'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 32],
            [['uid'], 'unique'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Идентификатор',
            'name' => 'Наименование',
            'image' => 'Изображение',
            'descr' => 'Описание',
            'address' => 'Адрес',
            'verify' => 'Контроль',
            'protocol_id' => 'Протокол',
            'location_id' => 'Локация',
            'type_id' => 'Тип устройства',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function GetState(){
        $models = $this::findAll(['verify'=>1]);
        $html = '';
        foreach ($models as $model){
            $vcc = Option::findOne(['device_id'=>$model->id,'alias'=>'vcc']);
            $val = $vcc->val;
            $min = $vcc->min_val;
            $max = $vcc->max_val;
            $mid = ($max + $min)/2;
            if($val==$max){
                $ico_vcc = '<i class="ace-icon fa fa-battery-full green"></i>';
            }
            elseif($val>$min && $val<($mid)){
                $ico_vcc = '<i class="ace-icon fa fa-battery-quarter orange"></i>';
            }
            elseif($val>$mid && $val<$max){
                $ico_vcc = '<i class="ace-icon fa fa-battery-three-quarters orange2"></i>';
            }
            elseif ($val<$min){
                $ico_vcc = '<i class="ace-icon fa fa-battery-empty red"></i>';
            }
            $rssi = Option::findOne(['device_id'=>$model->id,'alias'=>'rssi']);
            if($rssi->val > -70){
                $ico_rssi = '<i class="ace-icon fa fa-signal green"></i>';
            }
            elseif($rssi->val > -80 && $rssi->val <= -70) {
                $ico_rssi = '<i class="ace-icon fa fa-signal orange"></i>';
            }
            else{
                $ico_rssi = '<i class="ace-icon fa fa-signal red"></i>';
            }
            $time_stamp = strtotime(date('Y-m-d H:i:s')); //получаем текущую метку времени
            $time_last = strtotime($vcc->updated_at);
            $period = strtotime('+30 minutes',$time_last);
            if($time_stamp < $period){
                $stat = '<span class="label label-success arrowed-right arrowed-in">online</span>';
            }
            else{
                $stat = '<span class="label label-danger arrowed-right arrowed-in">offline</span>';
            }
            $html .= '<tr>
                                            <td><a href="/main/option?id='.$model->id.'">' . $model->name . '</a></td>

                                            <td>' . $ico_rssi . '&nbsp;&nbsp;&nbsp;<span class="badge">' . $rssi->val . $rssi->unit . '</span></td>

                                            <td>' . $ico_vcc . '</td>

                                            <td class="hidden-480">' . $stat . '</td>
                                        </tr>';
        }
        return $html;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterLog()
    {
        return $this->hasMany(CounterLog::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasMany(Option::className(), ['device_id' => 'id']);
    }

    public function getOptionCount(){
        $options = $this->option;
        return count($options);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarif()
    {
        return $this->hasOne(Tarif::className(), ['device_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(DeviceType::className(), ['id' => 'type_id']);
    }

    public function getTypeName()
    {
        $type = $this->type;
        return $type ? $type->name : '';
    }
}
