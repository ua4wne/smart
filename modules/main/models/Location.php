<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device[] $devices
 */
class Location extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'is_show'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['alias'], 'string', 'max' => 50],
            [['is_show'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'alias' => 'Текстовый код (EN)',
            'is_show' => 'Отображать на сайте',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['location_id' => 'id']);
    }

    public static function GetTabs(){
        $html = '<ul class="nav nav-tabs" id="LocationTab">';
        $locations = self::find()->select(['id','name','alias'])->where('is_show=1')->orderBy(['name' => SORT_ASC,])->all();
        $k=0;
        foreach ($locations as $location){
            if($k==3)
                $html .= '<li class="active">';
            else
                $html .= '<li>';
            $html .= '<a data-toggle="tab" href="#'.$location->alias.'">' .
                                    $location->name
                                . '</a>
                            </li>';
            $k++;
        }
        $html .= '</ul>';
        $k=0;
        $html .= '<div class="tab-content">';
        foreach ($locations as $location){
            //определяем кол-во устройств в помещении, которые контролируются автоматически
            $devices = Device::find()->select('id')->where(['location_id'=>$location->id,'verify'=>1])->all();
            if($k==0)
                $html .= '<div id="'.$location->alias.'" class="tab-pane fade in active">';
            else
                $html .= '<div id="'.$location->alias.'" class="tab-pane fade in">';
            if(!empty($devices)){ //есть такие устройства
                //определяем device_id
                $device_id = array();
                $i = 0;
                foreach ($devices as $device){
                    array_push($device_id, $device->id);
                    $i++;
                }
                //определяем параметры, привязанные к этим устройствам
                $params = Option::find()->where(['device_id' => $device_id])->andWhere(['not in','alias',array('vcc','rssi')])->all();
                $pcount = Option::find()->where(['device_id' => $device_id])->andWhere(['not in','alias',array('vcc','rssi')])->count();
                $step = 12/$pcount;
                $switch = '';
                $gaude = '';
                foreach ($params as $param){
                    if($param->alias == 'state'){
                        $topic=$param->topic->name;
                        $check='';
                        if($param->val)
                            $check='checked="checked"';
                        $switch .= '<label>
                                        <input type="hidden" name="'.$topic.'">
                                        <input type="checkbox" name="switch-'.$param->id.'" id = "'.$param->id.'" class="ace ace-switch ace-switch-7" '.$check.' >
                                        <span class="lbl">&nbsp;'.$param->name.'</span>
                                    </label>';
                    }
                    else{
                        $gaude .= '<div class="col-md-'.$step.'">
                                        <div id="'.$param->alias.'" class="gauge" data-value="'.$param->val.'" data-min="'.$param->min_val.'" data-max="'.$param->max_val.'" data-gaugeWidthScale="0.6"></div>
                                    </div>';
                    }
                }

                $html .= '     <div class="row">
                                    <div class="col-md-4">' .
                                        $switch
                                    . '</div>' .
                                        $gaude
                                . '</div>
                            </div>';
            }
            else{
                $html .= '     <div class="row">
                                    
                                </div>
                        </div>';
            }

            $k++;
        }
        $html .= '</div>';

        return $html;
    }
}
