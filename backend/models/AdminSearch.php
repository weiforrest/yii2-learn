<?php

namespace backend\models;

use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Admin;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class AdminSearch extends Admin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'nickname'], 'string'],
        ];
    }

    public function fields()
    {
        $fields = [
            'id',
            'username',
            'nickname',
            'status',
            'updated_at',
            'created_at'
        ];
        return $fields;
    }


    /**
     * {@inheritdoc}
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
        $query = $this->find();

        // add conditions that should always apply here

        $pagination  = [];
        $pagination['pageSize'] = $params['pageSize'] ? $params['pageSize']: 10;

        if($params['page']){
            // Yii2 current page number is zero-based
            $pagination['page'] = $params['page'] - 1;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination
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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if($params['username']){
            $query->andFilterWhere(['like', 'username', $params['username']]);
        }

        if($params['nickname']){
            $query->andFilterWhere(['like', 'nickname', $params['nickname']]);
        }

        

        return $dataProvider;
    }
}
