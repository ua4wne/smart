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
        $html = '<ul class="nav nav-tabs" id="myTab">';
        $locations = self::find()->select(['name','alias'])->where('is_show=1')->orderBy(['name' => SORT_ASC,])->all();
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
        return $html;
    }
}
