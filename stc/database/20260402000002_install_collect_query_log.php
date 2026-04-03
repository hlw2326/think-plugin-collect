<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallCollectQueryLog extends Migrator
{
    /**
     * 创建查询日志表
     */
    public function up(): void
    {
        $table = $this->table('plugin_collect_query_log', [
            'comment' => '插件-采集查询日志',
            'id'      => 'id',
        ]);

        $table->addColumn('cookie_id', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '关联CookieID'])
              ->addColumn('platform', 'string', ['limit' => 20, 'default' => '', 'comment' => '平台标识（dy/ks/bili/xhs/sph/tk）'])
              ->addColumn('channel', 'string', ['limit' => 20, 'default' => '', 'comment' => '渠道类型（web/h5/app）'])
              ->addColumn('action', 'string', ['limit' => 30, 'default' => '', 'comment' => '查询动作（verify/user_info/feeds/content_info）'])
              ->addColumn('param', 'text', ['comment' => '查询参数（uid/content_id等）'])
              ->addColumn('http_code', 'integer', ['limit' => 5, 'default' => 0, 'signed' => false, 'comment' => 'HTTP状态码'])
              ->addColumn('exec_time', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '执行耗时(毫秒)'])
              ->addColumn('result_code', 'integer', ['limit' => 1, 'default' => 1, 'signed' => false, 'comment' => '结果码(0失败,1成功)'])
              ->addColumn('result_msg', 'string', ['limit' => 500, 'default' => '', 'comment' => '错误信息'])
              ->addColumn('result_data', 'text', ['comment' => '结果数据摘要(JSON,最多前2000字符)'])
              ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '请求来源IP'])
              ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '查询时间'])
              ->addIndex('platform')
              ->addIndex('action')
              ->addIndex('result_code')
              ->addIndex('create_at')
              ->addIndex('cookie_id')
              ->save();
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_query_log')->drop();
    }
}
