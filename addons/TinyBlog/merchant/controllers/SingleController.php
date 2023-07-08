<?php

namespace addons\TinyBlog\merchant\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\enums\StatusEnum;
use addons\TinyBlog\common\models\Single;

/**
 * Class SingleController
 * @package addons\TinyBlog\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SingleController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Single
     */
    public $modelClass = Single::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
