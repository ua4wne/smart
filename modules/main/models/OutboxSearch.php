<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StockSearch represents the model behind the search form about `app\modules\main\models\Stock`.
 */
class OutboxSearch extends Outbox
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_new'], 'integer'],
            [['from', 'to', 'msg'], 'string'],
            [['created_at'], 'safe'],
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
        $query = Outbox::find();

        //$this->findName($this->material_id)

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
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
            //'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'msg' => $this->msg,
            'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);
        /*if(strlen($this->msg))
            $query->andFilterWhere(['like', 'msg', $this->msg]);
        if(strlen($this->created_at))
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);*/

        return $dataProvider;
    }
}
