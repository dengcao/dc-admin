<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

namespace app\admin\controller;

use app\admin\model\MemberGroup as MemberGroupModel;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class MemberGroup
{
    protected $middleware = [
        'dengcao_auth' => ['except' => ''],//验证是否管理员
    ];

    public function __construct(){
        dc_auth("mbr_group");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["member_group_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
        }
        View::assign([
            'member_group_limit'  => $limit
        ]);
        // 模板输出
        return View::fetch('member_group/index');
    }

    public function add()
    {
        // 模板输出
        return View::fetch('member_group/add');
    }

    public function addSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $update_data = Request::param('', '', 'filter_sql');//过滤注入
        $update_data["is_enabled"] = isset($update_data["is_enabled"]) ? $update_data["is_enabled"] : 0;
        $update_data["listorder"] = isset($update_data["listorder"]) ? intval($update_data["listorder"]) : 0;

        $groupid = Db::name('member_group')->insertGetId($update_data);

        if ($groupid > 0) {
            write_syslog(array("log_content"=>"新增用户组：".$update_data["group_name"]."，ID：".$groupid));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "添加成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $groupid = Request::param("groupid", '', 'filter_sql');
        if (!is_numeric($groupid)) {
            dengcao_error("参数错误", "", 1);
        }
        $member_group = MemberGroupModel::where("groupid", "=", $groupid)->findOrEmpty();
        if ($member_group->isEmpty()) {
            dengcao_error("[ID:" . $groupid . "]用户组不存在。", "", 1);
        } else {
            View::assign([
                'member_group' => $member_group
            ]);
        }

        // 模板输出
        return View::fetch('member_group/edit');
    }

    public function editSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $update_data = Request::param('', '', 'filter_sql');//过滤注入
        $update_data["is_enabled"] = isset($update_data["is_enabled"]) ? $update_data["is_enabled"] : 0;
        $update_data["listorder"] = isset($update_data["listorder"]) ? intval($update_data["listorder"]) : 0;
        $update_field = ['roles', 'group_name', 'group_remarks', 'is_enabled','listorder'];//允许更新的字段

        $update = MemberGroupModel::where("groupid", "=", $update_data["groupid"])->findOrEmpty();
        if ($update->isEmpty()) { //数据不存在
            $update_result = false;
        } else {
            $update_result = $update->allowField($update_field)->save($update_data);
        }

        if ($update_result) {
            write_syslog(array("log_content"=>"修改会员组：".$update_data["group_name"]."，ID：".$update_data["groupid"]));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "保存成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $groupid = Request::param("groupid", '', 'filter_sql');
        if (!is_numeric($groupid)) {
            dengcao_error("参数错误", "", 1);
        }
        $member_group = MemberGroupModel::where("groupid", "=", $groupid)->withAttr('is_enabled', function ($value) {
            $is_enabled = [0 => '<i class="layui-icon layui-icon-close hese"></i>', 1 => '<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $is_enabled[$value];
        })->findOrEmpty();
        if ($member_group->isEmpty()) {
            dengcao_error("[ID:" . $groupid . "]用户组不存在。", "", 1);
        } else {
            View::assign([
                'member_group' => $member_group
            ]);
        }
        // 模板输出
        return View::fetch('member_group/view');
    }

    public function get()//获取用户组数据
    {
        $page = Request::param("page");
        if (!is_numeric($page)) {
            $page = 1;
        }
        $limit = Request::param("limit");
        if (!is_numeric($limit)) {
            $web_config=get_web_config();
            $limit=$web_config["member_group_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }
        $list = MemberGroupModel::order('listorder', 'desc')->withAttr('is_enabled', function ($value) {
        $is_enabled = [0 => '<i class="layui-icon layui-icon-close hese"></i>', 1 => '<i class="layui-icon layui-icon-ok olivedrab"></i>'];
        return $is_enabled[$value];
    });
        $keyword = Request::param("keyword", '', 'filter_sql');
        if ($keyword) {
            $list = $list->whereOr([["group_name", "like", "%" . $keyword . "%"], ["group_remarks", "like", "%" . $keyword . "%"], ["roles", "like", "%" . $keyword . "%"]]);
        }
        $list = $list->paginate([
            'list_rows' => $limit,//每页数量
            'page' => $page,//当前页
        ]);
        return json($list);
    }

    public function getRolesConfig()//获取用户组配置的数据
    {
        $init_config = Config::get("app.dengcao_member_role_auths");
        $list = [];
        foreach ($init_config as $key => $val) {
            $list[] = array("role" => $key, "name" => $val["name"], "remarks" => $val["remarks"]);
        }
        return json($list);
    }

    public function getRolesConfigOne()//获取某个用户组的权限数据，新方法
    {
        $show = Request::param("show", '', 'filter_sql');//输出类型，view or edit
        //$show = isset($show) ? $show : "view";
        if ($show == "edit") {
            $is_own_key = "LAY_CHECKED";
            $is_own_ok = true;
            $is_own_no = false;
        } else {
            $is_own_key = "is_own";
            $is_own_ok = "<i class=\"layui-icon layui-icon-ok olivedrab\"></i>";
            $is_own_no = "<i class=\"layui-icon layui-icon-close hese\"></i>";
        }

        $groupid = Request::param("groupid", '', 'filter_sql');
        if (!is_numeric($groupid)) {
            return json(array());
        }
        $roles = MemberGroupModel::where("groupid", "=", $groupid)->findOrEmpty();
        if (!$roles->isEmpty()) {
            $role = $roles->roles;//获取role_id对应用户组的权限
        }

        $init_config = Config::get("app.dengcao_member_role_auths");
        $list = [];

        foreach ($init_config as $key => $val) {
            if (false !== strpos($role, $key)) {//拥有权限
                $is_own = $is_own_ok;
            } else {
                $is_own = $is_own_no;
            }
            $list[] = array($is_own_key => $is_own, "role" => $key, "name" => $val["name"], "remarks" => $val["remarks"]);
        }

        return json($list);
    }


    public function delete()//删除数据
    {
        //执行删除
        $groupid = Request::param("groupid", '', 'filter_sql');
        $del_num = 0;
        if ($groupid) {
            $del_num = MemberGroupModel::where("groupid", "in", $groupid)->delete();
        }
        if ($del_num > 0) {
            write_syslog(array("log_content"=>"删除用户组(ID)：".$groupid));//记录系统日志
            $list = array("code" => 1, "del_num" => $del_num);
        } else {
            $list = array("code" => 0, "del_num" => 0);
        }

        return json($list);
    }

}
