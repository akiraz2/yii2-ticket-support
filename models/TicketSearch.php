<?php
/**
 * @author akiraz@bk.ru
 * @link https://github.com/akiraz2/yii2-ticket-support
 * @copyright 2018 akiraz2
 * @license MIT
 */


namespace akiraz2\support\models;

use common\models\Account;
use MongoDB\BSON\UTCDateTime;
use akiraz2\support\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form about `akiraz2\ticket\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    use ModuleTrait;

    public $userSearch = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'title', 'status', 'created_at', 'user_id', 'hash_id', 'id'], 'safe'],
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
        $query = Ticket::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if ($this->userSearch) {
            $query->andFilterWhere(['user_id' => Yii::$app->user->id]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            'hash_id' => $this->hash_id,
            'category_id' => $this->category_id,
            'status' => $this->status,
            //'user_id' => $this->user_id,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        if (!empty($this->user_id)) {
            if ($this->getModule()->isMongoDb()) {
                $key = '_id';
            } else {
                $key = 'id';
            }
            $ids = [];
            $userNameField = $this->getModule()->userName;
            $owners = $this->getModule()->userModel::find()->select([$key])->where([
                'like',
                $userNameField,
                $this->user_id
            ])->asArray()->all();
            foreach ($owners as $owner) {
                if ($this->getModule()->isMongoDb()) {
                    $ids[] = (string)$owner[$key];
                } else {
                    $ids[] = (int)$owner[$key];
                }
            }
            $query->andFilterWhere(['user_id' => empty($ids) ? '0' : $ids]);
        }

        if (!empty($this->created_at)) {
            if (is_a($this, '\yii\mongodb\ActiveRecord')) {
                $query->andFilterWhere([
                    'created_at' => ['$gte' => new UTCDateTime(strtotime($this->created_at) * 1000)],
                ])->andFilterWhere([
                    'created_at' => ['$lt' => new UTCDateTime((strtotime($this->created_at) + 86400) * 1000)],
                ]);
            } else {
                $query->andFilterWhere([
                    'DATE(CONVERT_TZ(FROM_UNIXTIME(`created_at`), :UTC, :ATZ))' => $this->created_at,
                ])->params([
                    ':UTC' => '+00:00',
                    ':ATZ' => date('P')
                ]);
            }

        }

        return $dataProvider;
    }
}
