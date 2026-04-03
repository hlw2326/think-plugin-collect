<?php
declare(strict_types=1);

namespace plugin\collect\controller;

use plugin\collect\model\PluginCollectTag;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 标签管理
 * @class Tag
 * @package plugin\collect\controller
 */
class Tag extends Controller
{
    /**
     * 标签列表
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        PluginCollectTag::mQuery()->layTable(function () {
            $this->title = '标签列表';
        }, function (QueryHelper $query) {
            $query->like('name,value');
            $query->equal('status');
            $query->dateBetween('create_at');
        });
    }

    /**
     * 添加标签
     * @auth true
     */
    public function add(): void
    {
        $this->_applyFormToken();
        $this->title = '添加标签';
        PluginCollectTag::mForm('tag/form');
    }

    /**
     * 编辑标签
     * @auth true
     */
    public function edit(): void
    {
        $this->_applyFormToken();
        $this->title = '编辑标签';
        PluginCollectTag::mForm('tag/form');
    }

    /**
     * 表单数据处理
     */
    protected function _form_filter(array &$data): void
    {
        if ($this->request->isPost()) {
            $this->_vali([
                'name.require'  => '标签分类名称不能为空！',
                'name.max:50'  => '标签分类名称不能超过50字！',
                'value.require' => '子标签不能为空！',
            ]);
            $data['value'] = implode(',', array_filter(array_map('trim', explode(',', $data['value'] ?? ''))));
        }
    }

    /**
     * 修改状态
     * @auth true
     */
    public function state(): void
    {
        PluginCollectTag::mSave($this->_vali([
            'status.in:0,1'   => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除标签
     * @auth true
     */
    public function remove(): void
    {
        PluginCollectTag::mDelete();
    }
}
