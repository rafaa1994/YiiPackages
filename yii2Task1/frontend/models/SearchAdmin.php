<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;

class SearchAdmin extends Admin {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['username', 'surname', 'email', 'company_id', 'slug'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Admin::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // join table companies to help search companies name by companies_id

        $query->joinWith('companies');

        $query->andFilterWhere([
            'id' => $this->id,
            'username' => $this->username,
            'surname' => $this->surname,
            'email' => $this->email,
            'slug' => $this->slug,
        ]);
        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'surname', $this->surname])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'companies.name', $this->company_id]);

        $dataProvider->pagination->pageSize = 10;

        return $dataProvider;
    }

}
