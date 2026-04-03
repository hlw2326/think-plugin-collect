<?php
declare(strict_types=1);

namespace plugin\collect\controller;

use plugin\collect\model\PluginCollectCookie;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * Cookie管理
 * @class Cookie
 * @package plugin\collect\controller
 */
class Cookie extends Controller
{
    /**
     * Cookie列表
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        $platforms = PluginCollectCookie::getPlatformTypes();
        $current   = trim(input('platform', ''));

        $tabs = ['' => ['name' => lang('全部Cookie'), 'count' => 0]];
        foreach ($platforms as $key => $item) {
            $tabs[$key] = ['name' => $item['label'], 'count' => 0];
        }
        $total = PluginCollectCookie::mk()->group('platform')->column('count(id) as cnt,platform', 'platform');
        foreach ($total as $row) {
            $tabs['']['count'] += $row['cnt'];
            if (isset($tabs[$row['platform']])) {
                $tabs[$row['platform']]['count'] = $row['cnt'];
            }
        }

        PluginCollectCookie::mQuery()->layTable(function () use ($tabs, $platforms) {
            $this->title      = 'Cookie管理';
            $this->platforms  = $platforms;
            $this->channels   = PluginCollectCookie::getChannelTypes();
            $this->tabs       = $tabs;
        }, function (QueryHelper $query) use ($current) {
            $query->like('name,account');
            $query->equal('platform,status');
            $query->dateBetween('create_at,last_verify_time');
            if ($current !== '') {
                $query->equal('platform', $current);
            }
        });
    }

    /**
     * 添加Cookie
     * @auth true
     */
    public function add(): void
    {
        $this->_applyFormToken();
        $this->title = '添加Cookie';
        PluginCollectCookie::mForm('form');
    }

    /**
     * 编辑Cookie
     * @auth true
     */
    public function edit(): void
    {
        $this->_applyFormToken();
        $this->title = '编辑Cookie';
        PluginCollectCookie::mForm('form');
    }

    /**
     * 表单数据处理
     */
    protected function _form_filter(array &$data): void
    {
        $this->platforms = PluginCollectCookie::getPlatformTypes();
        $this->channels  = PluginCollectCookie::getChannelTypes();
    }

    /**
     * 修改状态
     * @auth true
     */
    public function state(): void
    {
        PluginCollectCookie::mSave($this->_vali([
            'status.in:0,1'   => '状态值范围异常！',
            'status.require'  => '状态值不能为空！',
        ]));
    }

    /**
     * 删除Cookie
     * @auth true
     */
    public function remove(): void
    {
        PluginCollectCookie::mDelete();
    }
}
