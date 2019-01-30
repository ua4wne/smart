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
            if($k==0)
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
                //$pcount = Option::find()->where(['device_id' => $device_id])->andWhere(['not in','alias',array('vcc','rssi')])->count();
                $step = 0;
                $html .= '<div class="row">
                            <div class="col-xs-12">';
                foreach ($params as $param){
                    if($param->alias == 'state' || $param->alias == 'light'){
                        $topic_id = Topic::findOne(['option_id'=>$param->id])->topic_id;
                        $topic=MqttData::findOne($topic_id)->topic;
                        $check='';
                        if($param->val)
                            $check='checked="checked"';
                        $html .= '<div class="infobox infobox-blue2">                                        
								        <div class="infobox-data">
										    <input type="hidden" name="'.$topic.'">
											<label>
												<input type="checkbox" name="switch-'.$param->id.'" id = "'.$param->id.'" class="ace ace-switch ace-switch-4 btn-rotate" '.$check.' >
												<span class="lbl"></span>
											</label>

											<div class="infobox-content">
												<span class="infobox-text">'.$param->name.'</span>
											</div>
										</div>
								</div>';
                    }
                    elseif($param->alias == 'alarm'){
                        if($param->val){
                            $html.= '<div class="infobox infobox-red">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-bell-o red"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        else{
                            $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-bell-o green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                    }
                    elseif($param->alias == 'celsio' || $param->alias == 'humidity' || $param->alias == 'power'){
                        if($param->alias == 'celsio'){
                            if($param->val <= 15)
                                $color = 'data-color="#87CEEB"';
                            if($param->val > 15 && $param->val < 30)
                                $color = 'data-color="#87B87F"';
                            if($param->val >= 30)
                                $color = 'data-color="#D15B47"';
                        }
                        if($param->alias == 'humidity'){
                            if($param->val <= 50)
                                $color = 'data-color="#FFB935"';
                            if($param->val > 50 && $param->val < 70)
                                $color = 'data-color="#87B87F"';
                            if($param->val > 70)
                                $color = 'data-color="#D15B47"';
                        }
                        $html.= '<div class="infobox infobox-blue2">
											<div class="infobox-progress">
												<div class="easy-pie-chart percentage" data-percent="' . $param->val . '" ' . $color .'>
													<span class="percent">' . $param->val . '</span>
												</div>
											</div>

											<div class="infobox-data">
												<span class="infobox-text">' . $param->val . $param->unit . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
										</div>';

                    }
                    elseif($param->alias == 'pressure'){
                        if($param->val <= 740){
                            $html.= '<div class="infobox infobox-blue">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-arrow-down blue"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        if($param->val > 740 && $param->val < 750){
                            $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-thumbs-o-up green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                        if($param->val >= 750){
                            $html.= '<div class="infobox infobox-blue">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-arrow-up blue"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">' . $param->val . '</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                        }
                    }
                    else{
                        $html.= '<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-comment green"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">Норма</span>
												<div class="infobox-content">'.$param->name.'</div>
											</div>
									</div>';
                    }
                }

                $html .= '    </div>
                            </div>
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
