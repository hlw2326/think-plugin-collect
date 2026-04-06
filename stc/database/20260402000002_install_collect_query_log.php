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
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-采集查询日志',
        ]);

        PhinxExtend::upgrade($table, [
            ['cookie_id', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '关联CookieID']],
            ['platform', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '平台标识（dy/ks/bili/xhs/sph/tk）']],
            ['channel', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '渠道类型（web/h5/app）']],
            ['action', 'string', ['limit' => 30, 'default' => '', 'null' => true, 'comment' => '查询动作（verify/user_info/feeds/content_info）']],
            ['param', 'text', ['default' => null, 'null' => true, 'comment' => '查询参数（uid/content_id等）']],
            ['http_code', 'integer', ['limit' => 5, 'default' => 0, 'null' => true, 'comment' => 'HTTP状态码']],
            ['exec_time', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '执行耗时(毫秒)']],
            ['result_code', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '结果码(0失败,1成功)']],
            ['result_msg', 'string', ['limit' => 500, 'default' => '', 'null' => true, 'comment' => '错误信息']],
            ['result_data', 'text', ['default' => null, 'null' => true, 'comment' => '结果数据摘要(JSON,最多前2000字符)']],
            ['ip', 'string', ['limit' => 50, 'default' => '', 'null' => true, 'comment' => '请求来源IP']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '查询时间']],
        ], [
            'platform',
            'action',
            'result_code',
            'create_at',
            'cookie_id',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_query_log')->drop();
    }
}
