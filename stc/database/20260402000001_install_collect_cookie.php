<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallCollectCookie extends Migrator
{
    /**
     * 创建 Cookie 管理表
     */
    public function up(): void
    {
        $table = $this->table('plugin_collect_cookie', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-cookie管理表',
        ]);

        PhinxExtend::upgrade($table, [
            [
                'name',
                'string',
                ['limit' => 255, 'default' => '', 'null' => true, 'comment' => '名称']
            ],
            [
                'account',
                'string',
                ['limit' => 255, 'default' => '', 'null' => true, 'comment' => '关联账号']
            ],
            [
                'platform',
                'string',
                ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '平台标识（dy/ks/bili/xhs/sph/tk）']
            ],
            [
                'channel',
                'string',
                ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '渠道类型（web/h5/app）']
            ],
            [
                'cookie',
                'text',
                ['default' => null, 'null' => true, 'comment' => 'Cookie内容']
            ],
            [
                'status',
                'integer',
                ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(0禁用,1启用)']
            ],
            [
                'last_verify_time',
                'integer',
                ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '最后验证时间戳']
            ],
            [
                'last_use_time',
                'integer',
                ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '最后使用时间戳']
            ],
            [
                'error_count',
                'integer',
                ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '连续失败次数']
            ],
            [
                'create_at',
                'datetime',
                ['default' => null, 'null' => true, 'comment' => '创建时间']
            ],
            [
                'update_at',
                'datetime',
                ['default' => null, 'null' => true, 'comment' => '更新时间']
            ],
        ], [
            'platform',
            'status',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_cookie')->drop();
    }
}
