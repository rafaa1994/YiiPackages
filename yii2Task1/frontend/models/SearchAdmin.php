<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use common\models\Admin;

class SearchAdmin extends Admin {

    /**
     * @inheritdoc
     */
    
    public function rules() {
        return [
            [['id'], 'integer'],
            [['name', 'surname', 'email', 'company_id', 'slug'], 'safe'],
        ];
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

        $query->andFilterWhere([
            Admin::tableName().'.id' => $this->id,
        ]);
        
        $query->joinWith('company');
       
        $query->andFilterWhere(['like', Admin::tableName().'.id', $this->id])
                ->andFilterWhere(['like', Admin::tableName().'.name', $this->name])
                ->andFilterWhere(['like', 'surname', $this->surname])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'companies.name', $this->company_id]);

        $dataProvider->pagination->pageSize = 10;

        return $dataProvider;
    }

}
