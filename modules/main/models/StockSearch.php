<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Stock;

/**
 * StockSearch represents the model behind the search form about `app\modules\main\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cell_id', 'quantity', 'unit_id'], 'integer'],
            [['price'], 'number'],
            [['material_id'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Stock::find();

        //$this->findName($this->material_id)

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cell_id' => $this->cell_id,
            //'material_id' => $this->material_id,
            'quantity' => $this->quantity,
            'unit_id' => $this->unit_id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if(strlen($this->material_id))
        $query->andFilterWhere(['in','material_id',self::findName($this->material_id)]);

        return $dataProvider;
    }

    public static function findName($name){
        if (($model = Material::find()->select('id')->where(['like','name',$name])->asArray()->all()) !== null){
            $str = '';
            foreach ($model as $val){
                $str.=$val['id'].',';
            }
            return $str;
        }
    }
}
