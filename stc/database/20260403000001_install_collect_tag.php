<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallCollectTag extends Migrator
{
    /**
     * 创建标签管理表
     */
    public function up(): void
    {
        $table = $this->table('plugin_collect_tag', [
            'comment' => '插件-标签管理',
            'id'      => 'id',
        ]);

        $table->addColumn('name', 'string', ['limit' => 255, 'default' => '', 'comment' => '标签分类名称'])
              ->addColumn('value', 'text', ['comment' => '子标签（逗号分隔）'])
              ->addColumn('sort', 'integer', ['limit' => 11, 'default' => 0, 'signed' => false, 'comment' => '排序权重'])
              ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'signed' => false, 'comment' => '状态(0禁用,1启用)'])
              ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
              ->addColumn('update_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
              ->addIndex('name')
              ->addIndex('status')
              ->addIndex('sort')
              ->addIndex('create_at')
              ->save();
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_tag')->drop();
    }
}
