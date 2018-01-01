<?php
/**
 * Created by PhpStorm.
 * User: dervish
 * Date: 01.01.2018
 * Time: 18:15
 */

namespace app\modules\main\models;


use yii\base\Model;

class ReportFilter extends Model
{
    public $option_id;
    public $start;
    public $finish;

    public function rules()
    {
        return [
            [['option_id', 'start', 'finish'], 'required'],
            [['option_id'], 'integer'],
            [['start', 'finish'], 'string', 'max' => 19],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Параметр',
            'start' => 'Начало периода',
            'finish' => 'Конец периода'
        ];
    }
}