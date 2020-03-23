<?php

namespace backend\models\settings;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\settings\SystemConfig;

/**
 * SystemConfigSearch represents the model behind the search form of `app\models\SystemConfig`.
 */
class SystemConfigSearch extends SystemConfig
{
    /**
     * @var string use to filter created date
     */
    public $created_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'json_config', 'created_date', 'status'], 'safe'],
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
        $query = SystemConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'json_config', $this->json_config])
            ->andFilterWhere(['like', 'status', $this->status]);

        $query->orderBy('created_at DESC');

        if (isset($this->created_date)) {
            $query->andFilterWhere(['DATE(created_at)' => $this->created_date]);
        }

        return $dataProvider;
    }
}
