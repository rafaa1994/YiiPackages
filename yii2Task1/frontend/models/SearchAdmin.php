<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;


class SearchPlayers extends Admin
{
    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            [['id', 'company_id'], 'integer'],
            [['username', 'surname', 'slug', 'email'], 'safe'],
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
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'username' => $this->username,
            'surname' => $this->surname,
            'slug' => $this->slug,
            'company_id' => $this->company_id,
            'email' => $this->email,
        ]);

        $query->andFilterWhere(['like','id', $this->id])
            ->andFilterWhere(['like', 'username', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_id', $this->company_id]);

        return $dataProvider;
    }
}
