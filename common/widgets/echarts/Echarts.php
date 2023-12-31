<?php

namespace common\widgets\echarts;

use Yii;
use yii\base\Widget;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\widgets\echarts\assets\AppAsset;

/**
 * Class Echarts
 * @package common\widgets\echarts
 * @author jianyan74 <751393839@qq.com>
 */
class Echarts extends Widget
{
    /**
     * @var array
     */
    public $config = [];

    /**
     * 默认主题
     *
     * @var string
     */
    public $theme = 'line-bar';

    /**
     * 模板主题
     *
     * @var string
     */
    public $themeJs = 'walden';

    /**
     * 默认主题配置
     *
     * @var array
     */
    public $themeConfig = [
        'today' => '今天',
        'yesterday' => '昨天',
        'this7Day' => '近7天',
        'this30Day' => '近30天',
        // 'thisWeek' => '本周',
        // 'thisMonth' => '本月',
        'thisYear' => '今年',
        // 'lastYear' => '去年',
        'customData' => '自定义区间'
    ];

    /**
     * 字段
     *
     * @var array
     */
    public $columns = [
//        [
//            'name' => 'test',
//            'value' => 2022,
//            'type' => 'radioList',
//            'items' => [
//                2022 => '2022年',
//                2023 => '2023年'
//            ],
//            'col' => 4,
//        ]
    ];

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $this->boxId = StringHelper::uuid('uniqid');
        $this->config = ArrayHelper::merge([
            'server' => '',
            'height' => '500px'
        ], $this->config);

        $this->themeConfig = ArrayHelper::merge([
        ], $this->themeConfig);
    }

    /**
     * @return string
     */
    public function run()
    {
        // 注册资源
        $this->registerClientScript();

        if (empty($this->theme)) {
            return false;
        }

        return $this->render($this->theme, [
            'boxId' => $this->boxId,
            'config' => $this->config,
            'themeJs' => $this->themeJs,
            'themeConfig' => $this->themeConfig,
            'columns' => $this->columns,
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        AppAsset::register($view);

        if ($this->theme == 'bmap') {
            $view->registerJsFile('https://api.map.baidu.com/api?v=2.0&ak=' . Yii::$app->services->config->backendConfig('map_baidu_ak'));
        }
    }
}
