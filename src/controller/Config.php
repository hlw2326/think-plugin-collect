<?php
declare(strict_types=1);

namespace plugin\collect\controller;

use think\admin\Controller;

/**
 * 采集配置
 * @class Config
 * @package plugin\collect\controller
 */
class Config extends Controller
{
    /**
     * 基础配置
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        $this->title = '采集配置';
        $this->platforms = [
            'default' => lang('默认'),
            'toutiao' => 'TouTiao',
            'weibo' => lang('微博'),
            'douyin' => lang('抖音'),
            'xiaohongshu' => lang('小红书'),
        ];

        if ($this->request->isPost()) {
            $post = $this->request->post();
            foreach ($post as $key => $value) {
                sysconf($key, $value);
            }
            $this->success('配置保存成功！');
        } else {
            $this->fetch();
        }
    }
}
