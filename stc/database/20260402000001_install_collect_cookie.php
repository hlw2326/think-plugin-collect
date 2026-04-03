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
            'comment' => '插件-cookie管理表',
            'id'      => 'id',
        ]);

        $table->addColumn('name', 'string', ['limit' => 255, 'default' => '', 'comment' => '名称'])
              ->addColumn('account', 'string', ['limit' => 255, 'default' => '', 'comment' => '关联账号'])
              ->addColumn('platform', 'string', ['limit' => 20, 'default' => '', 'comment' => '平台标识（dy/ks/bili/xhs/sph/tk）'])
              ->addColumn('channel', 'string', ['limit' => 20, 'default' => '', 'comment' => '渠道类型（web/h5/app）'])
              ->addColumn('cookie', 'text', ['comment' => 'Cookie内容'])
              ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'signed' => false, 'comment' => '状态(0禁用,1启用)'])
              ->addColumn('last_verify_time', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '最后验证时间戳'])
              ->addColumn('last_use_time', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '最后使用时间戳'])
              ->addColumn('error_count', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '连续失败次数'])
              ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
              ->addColumn('update_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
              ->addIndex('platform')
              ->addIndex('status')
              ->addIndex('create_at')
              ->save();
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_cookie')->drop();
    }
}
