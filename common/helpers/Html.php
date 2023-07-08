<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseHtml;
use common\enums\StatusEnum;

/**
 * Class Html
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Html extends BaseHtml
{
    /**
     * 创建
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function create(array $url, $content = '创建', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => "btn btn-primary btn-sm"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 编辑
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function edit(array $url, $content = '编辑', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'btn btn-primary btn-sm',
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 删除
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function delete(array $url, $content = '删除', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'btn btn-danger btn-sm',
            'onclick' => "rfDelete(this);return false;"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 普通按钮
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function linkButton(array $url, $content, $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => "btn btn-white btn-sm"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 导出按钮
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function export(array $url, $content, $options = [])
    {
        $url = ArrayHelper::merge($url, Yii::$app->request->get());

        $options = ArrayHelper::merge([
            'class' => "btn btn-white btn-sm"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 状态标签
     *
     * @param int $status
     * @return mixed
     */
    public static function status($status = 1, $options = [])
    {
        if (!self::beforVerify('ajax-update')) {
            return '';
        }

        $listBut = [
            StatusEnum::DISABLED => self::tag('span', '启用', array_merge(
                [
                    'class' => "btn btn-success btn-sm",
                    'data-toggle' => 'tooltip',
                    'data-original-title' => '点击启用',
                    'onclick' => "rfStatus(this)"
                ],
                $options
            )),
            StatusEnum::ENABLED => self::tag('span', '禁用', array_merge(
                [
                    'class' => "btn btn-default btn-sm",
                    'data-toggle' => 'tooltip',
                    'data-original-title' => '点击禁用',
                    'onclick' => "rfStatus(this)"
                ],
                $options
            )),
        ];

        return $listBut[$status] ?? '';
    }

    /**
     * @param string $text
     * @param null $url
     * @param array $options
     * @return string
     */
    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {
            // 权限校验
            if (!self::beforVerify($url)) {
                return '';
            }

            $options['href'] = Url::to($url);
        }

        return static::tag('a', $text, $options);
    }

    /**
     * 排序
     *
     * @param $value
     * @return string
     */
    public static function sort($value, $options = [])
    {
        // 权限校验
        if (!self::beforVerify('ajax-update')) {
            return $value;
        }

        $options = ArrayHelper::merge([
            'class' => 'form-control',
            'onblur' => 'rfSort(this)',
            'style' => 'min-width:55px'
        ], $options);

        return self::input('text', 'sort', $value, $options);
    }

    /**
     * 缩减显示字符串
     *
     * @param $string
     * @param int $num
     * @return string
     */
    public static function textNewLine($string, $num = 36, $cycle_index = 3)
    {
        if (empty($string)) {
            return  '';
        }

        return self::tag('span', implode('<br>', StringHelper::textNewLine($string, $num, $cycle_index)), [
            'title' => $string,
        ]);
    }

    /**
     * 根据开始时间和结束时间发回当前状态
     *
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     * @return mixed
     */
    public static function timeStatus($start_time, $end_time)
    {
        $time = time();
        if ($start_time > $end_time) {
            return "<span class='label label-outline-danger'>有效期错误</span>";
        } elseif ($start_time > $time) {
            return "<span class='label label-outline-default'>未开始</span>";
        } elseif ($start_time < $time && $end_time > $time) {
            return "<span class='label label-outline-success'>进行中</span>";
        } elseif ($end_time < $time) {
            return "<span class='label label-outline-default'>已结束</span>";
        }

        return false;
    }

    /**
     * 由于ajax加载model有些控件会重新载入样式导致基础样式失调做的修复
     *
     * @return string|void
     */
    public static function modelBaseCss()
    {
        echo Html::cssFile(Yii::getAlias('@baseResources') . '/css/rageframe.css?v=' . time());

        Yii::$app->controller->view->registerCss(<<<Css
.modal {
    z-index: 999;
}

.modal-backdrop {
    z-index: 998;
}

Css
        );
    }

    /**
     * @param $route
     * @return bool
     */
    protected static function beforVerify($route)
    {
        // 未登录直接放行
        if (Yii::$app->user->isGuest) {
            return true;
        }

        is_array($route) && $route = $route[0];

        $route = Url::getAuthUrl($route);
        substr("$route", 0, 1) != '/' && $route = '/' . $route;

        // 判断是否在模块内容
        if (true === Yii::$app->params['inAddon']) {
            $route = StringHelper::replace('/addons/', '', $route);
        }

        return Auth::verify($route);
    }
}
