<?php

namespace backend\models\auth;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\auth\UserProfile;

/**
 * UserProfileSearch represents the model behind the search form of `app\models\UserProfile`.
 */
class UserProfileSearch extends UserProfile
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
            [['id', 'user_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'avatar', 'created_date', 'status'], 'safe'],
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
        $query = UserProfile::find();

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

        $query->joinWith(['user']);

        // grid filtering conditions
        $query->andFilterWhere([
            'user_profile.id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        if(isset($this->first_name)){
            $query->andFilterWhere(['like', 'first_name', $this->first_name]);
            $query->orFilterWhere(['like', 'last_name', $this->first_name]);
        }

        $query->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'user.status', $this->status]);

        $query->orderBy('user.created_at DESC');

        if (isset($this->created_date)) {
            $query->andFilterWhere(['DATE(user.created_at)' => $this->created_date]);
        }

        if(isset($this->email)){
            $query->andFilterWhere(['like','user.email',$this->email]);
        }

        return $dataProvider;
    }
}
