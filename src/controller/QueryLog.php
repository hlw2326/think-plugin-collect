<?php
declare(strict_types=1);

namespace plugin\collect\controller;

use plugin\collect\model\PluginCollectCookie;
use plugin\collect\model\PluginCollectQueryLog;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 查询日志管理
 * @class QueryLog
 * @package plugin\collect\controller
 */
class QueryLog extends Controller
{
    /**
     * 查询日志列表
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        $platforms = PluginCollectCookie::getPlatformTypes();
        $actions   = PluginCollectQueryLog::getActionTypes();

        $currentPlatform = trim(input('platform', ''));
        $currentAction   = trim(input('action', ''));

        $tabs = ['' => ['name' => lang('全部记录'), 'count' => 0]];
        foreach ($platforms as $key => $item) {
            $tabs["p_{$key}"] = ['name' => $item['label'], 'count' => 0];
        }
        $tabs['sep1'] = ['name' => '────────', 'count' => 0, 'disabled' => true];
        foreach ($actions as $key => $item) {
            $tabs["a_{$key}"] = ['name' => $item['label'], 'count' => 0];
        }

        $total = PluginCollectQueryLog::mk()->group('platform,action')
            ->column('count(id) as cnt,platform,action', 'platform,action');
        foreach ($total as $row) {
            $tabs['']['count'] += $row['cnt'];
            if (isset($tabs["p_{$row['platform']}"])) {
                $tabs["p_{$row['platform']}"]['count'] += $row['cnt'];
            }
            if (isset($tabs["a_{$row['action']}"])) {
                $tabs["a_{$row['action']}"]['count'] += $row['cnt'];
            }
        }

        PluginCollectQueryLog::mQuery()->layTable(function () use ($tabs, $platforms, $actions) {
            $this->title     = '查询日志';
            $this->platforms = $platforms;
            $this->actions   = $actions;
            $this->tabs      = $tabs;
        }, function (QueryHelper $query) use ($currentPlatform, $currentAction) {
            $query->like('param,ip');
            $query->equal('result_code');
            $query->dateBetween('create_at');
            if ($currentPlatform !== '') {
                $query->equal('platform', $currentPlatform);
            }
            if ($currentAction !== '') {
                $query->equal('action', $currentAction);
            }
        });
    }

    /**
     * 查看详情
     * @auth true
     */
    public function detail(): void
    {
        $id = input('id', 0);
        $this->vo = PluginCollectQueryLog::mk()->find($id);
        $this->platforms = PluginCollectCookie::getPlatformTypes();
        $this->actions = PluginCollectQueryLog::getActionTypes();
        $this->fetch();
    }

    /**
     * 清理日志
     * @auth true
     */
    public function clear(): void
    {
        $keepDays = input('keep_days', 7, 'intval');
        if ($keepDays < 1) {
            $keepDays = 7;
        }
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$keepDays} days"));
        $count = PluginCollectQueryLog::mk()->where('create_at', '<', $cutoff)->delete();
        sysoplog('采集查询日志', "清理 {$keepDays} 天前的查询日志，删除 {$count} 条记录");
        $this->success("清理完成，共删除 {$count} 条记录！");
    }
}
