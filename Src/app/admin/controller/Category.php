<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

namespace app\admin\controller;

use app\admin\model\Category as CategoryModel;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use think\facade\Cache;

class Category
{
    protected $types, $modelids;

    protected $middleware = [
        'dengcao_auth' => ['except' => ''],//验证是否管理员
    ];

    public function __construct()
    {
        dc_auth("category");//检测是否有权限
        $this->types = Config::get("app.dengcao_category_types");
        $this->modelids = Config::get("app.dengcao_category_modelid");
    }

    public function add()
    {
        $tree = new Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $categorys = array();
        $req_data = Request::param('', '', 'filter_sql');//过滤注入
        $parentid = isset($req_data['parentid']) ? intval($req_data['parentid']) : "";
        $result = get_category_data("", "", "", "",0);//读取数据库
        if (!empty($result)) {
            foreach ($result as $r) {
                $categorys[$r['catid']] = $r;
            }
        }
        $str = "<option value='\$catid' \$selected>\$spacer \$catname</option>";
        $source_string = '';
        $tree->init($categorys);
        $source_string .= $tree->get_tree(0, $str, $parentid);

        $types = Config::get("app.dengcao_category_types");
        $modelids = Config::get("app.dengcao_category_modelid");

        View::assign([
            'parentid_select' => $source_string,
            'types' => $types,
            'modelids' => $modelids
        ]);
        // 模板输出
        return View::fetch('category/add');
    }


    public function addSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $update_data = Request::param('', '', 'filter_sql');//过滤注入
        $update_data["ismenu"] = isset($update_data["ismenu"]) ? intval($update_data["ismenu"]) : 0;
        $update_data["listorder"] = isset($update_data["listorder"]) ? intval($update_data["listorder"]) : 0;

        $update_data=array_diff_key($update_data,["editorValue"=>""]);//按键值删除多余的数组

        $catid = Db::name('category')->insertGetId($update_data);

        category_repair();//修正分类数据

        if ($catid > 0) {
            $category = CategoryModel::where("catid", "=", $catid)->findOrEmpty();
            if (!$category->isEmpty()) {
                $update_listorder = $category->allowField(['listorder'])->save(array('listorder' => $catid));
            }
            write_syslog(array("log_content" => "新增分类：" . $update_data["catname"] . "，ID：" . $catid));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "添加成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "添加失败");
        }
        return json($list);
    }


    public function edit()
    {
        $catid = Request::param("catid", '', 'filter_sql');
        if (!is_numeric($catid)) {
            dengcao_error("参数错误", "", 1);
        }
        $category = CategoryModel::where("catid", "=", $catid)->findOrEmpty();
        if ($category->isEmpty()) {
            dengcao_error("[ID:" . $catid . "]分类不存在。", "", 1);
        }

        $tree = new Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $categorys = array();
        //$result = get_category_data("", "", "", "");//读取缓存
        $result = get_category_data("", "", "", "",0);//读取数据库
        if (!empty($result)) {
            foreach ($result as $r) {
                $categorys[$r['catid']] = $r;
            }
        }
        $str = "<option value='\$catid' \$selected>\$spacer \$catname</option>";
        $source_string = '';
        $tree->init($categorys);
        $source_string .= $tree->get_tree(0, $str, $category->parentid);

        $types = Config::get("app.dengcao_category_types");
        $modelids = Config::get("app.dengcao_category_modelid");

        View::assign([
            'parentid_select' => $source_string,
            'types' => $types,
            'modelids' => $modelids,
            'category' => $category
        ]);
        // 模板输出
        return View::fetch('category/edit');
    }

    public function editSave()
    {
        if (!Request::isAjax()) {
            // 如果不是AJAX
            return result_json(0, "error");
        }
        $update_data = Request::param('', '', 'filter_sql');//过滤注入
        $update_data["ismenu"] = isset($update_data["ismenu"]) ? intval($update_data["ismenu"]) : 0;
        $update_data["listorder"] = isset($update_data["listorder"]) ? intval($update_data["listorder"]) : 0;
        $update_field = ['parentid', 'catname', 'modelid', 'type', 'ismenu', 'image', 'url', 'listorder', 'description'];//允许更新的字段

        $category = CategoryModel::where("catid", "=", $update_data["catid"])->findOrEmpty();
        if ($category->isEmpty()) {//数据不存在
            $update_result = false;
        } else {
            $update_result = $category->allowField($update_field)->save($update_data);
        }

        if ($update_result) {
            category_repair();//修正分类数据
            write_syslog(array("log_content" => "修改分类：" . $update_data["catname"] . "，ID：" . $update_data["catid"]));//记录系统日志
            $list = array("code" => 1, "update_num" => 1, "msg" => "保存成功");
        } else {
            $list = array("code" => 0, "update_num" => 0, "msg" => "保存失败");
        }
        return json($list);
    }


    /**
     * 排序
     */
    public function listorder()
    {
        $update_data = Request::param('', '', 'filter_sql');//过滤注入
        foreach ($update_data['listorders'] as $catid => $listorder) {
            if (is_numeric($catid) && is_numeric($listorder)) {
                Db::name('category')->where('catid', $catid)->update(['listorder' => $listorder]);
            }
        }
        Cache::clear();//清空缓存
        dengcao_success("更新排序成功", Request::header("referer"), 1);
    }


    /**
     * 删除分类
     */
    public function delete()
    {
        $catid = Request::param("catid", '', 'filter_sql');
        if (!is_numeric($catid)) {
            dengcao_error("参数错误", "", 1);
        }
        $result = get_category_data("","","","",0);//直接读取数据库
        if(!empty($result)) {
            foreach ($result as $r) {
                $categorys[$r['catid']] = $r;
            }
        }
        $modelid = $categorys[$catid]['modelid'];
        $catname = $categorys[$catid]['catname'];
        $this->delete_child($catid, $modelid);
        $del_num=CategoryModel::where("catid","=",$catid)->delete();
        $this->delete_category_content($catid, $modelid);//删除分类下的文章、单页、图片等等数据
        if($del_num>0){
            write_syslog(array("log_content"=>"删除分类，catid：".$catid."，分类名：".$catname));//记录系统日志
            $list=array("code"=>1);
        }else{
            $list=array("code"=>0);
        }
        Cache::clear();//清空缓存
        return json($list);
    }

    /**
     * 递归删除分类
     * @param $catid 要删除的分类id
     * @param $modelid 模块ID
     * @return boolean
     */
    private function delete_child($catid, $modelid)
    {
        $catid = intval($catid);
        if (empty($catid)) return false;
        $categorys = CategoryModel::where("parentid", "=", $catid)->select();
        foreach($categorys as $key=>$category){
            $this->delete_child($category->catid, $category->modelid);
            $del_num=CategoryModel::where("catid","=",$category->catid)->delete();
            write_syslog(array("log_content"=>"删除子分类，catid：".$category->catid."，分类名：".$category->catname));//记录系统日志
            $this->delete_category_content($category->catid, $category->modelid);//删除分类下的文章、单页、图片等等数据
        }
        return true;
    }

    /**
     * 删除分类下的文章、单页、图片等等数据
     * @param $catid 要删除数据的分类id
     * @return boolean
     */
    private function delete_category_content($catid, $modelid)
    {
        switch ($modelid){
          case 0://删除内置模块，单页

                write_syslog(array("log_content"=>"批量删除单页内容，分类ID：".$catid));//记录系统日志
                break;
          case 1://删除文章操作

                write_syslog(array("log_content"=>"批量删除文章内容，分类ID：".$catid));//记录系统日志
                break;
          case 2:

                write_syslog(array("log_content"=>"批量删除下载内容，分类ID：".$catid));//记录系统日志
                break;
          case 3:

                write_syslog(array("log_content"=>"批量删除图片内容，分类ID：".$catid));//记录系统日志
                break;
          case 4:

                write_syslog(array("log_content"=>"批量删除视频内容，分类ID：".$catid));//记录系统日志
                break;
          default:
        }

        return true;
    }


    public function repair()//修复分类数据
    {
        $repair = category_repair();
        if ($repair) {
            write_syslog(array("log_content" => "修复分类数据"));//记录系统日志
            $list = array("code" => 1);
        } else {
            $list = array("code" => 0);
        }
        return json($list);
    }


    public function index()
    {
        $tree = new Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $categorys = array();
        $req_data = Request::param('', '', 'filter_sql');//过滤注入
        $parentid = isset($req_data['parentid']) ? intval($req_data['parentid']) : "";
        $result = get_category_data("", "", "", "",0);//读取数据库
        $html_root = "/";
        if (!empty($result)) {
            foreach ($result as $r) {
                $r['modelname'] = $this->modelids[$r['modelid']];
                $r['str_manage'] = '';
//                if (!$show_detail) {
//                    if ($r['parentid'] != $parentid) continue;
//                    $r['parentid'] = 0;
//                    $r['str_manage'] .= '<a href="#?m=admin&c=category&a=init&parentid=' . $r['catid'] . '&menuid=' . $_GET['menuid'] . '&s=' . $r['type'] . '&pc_hash=' . $_SESSION['pc_hash'] . '">管理子分类</a> | ';
//                }
                $r['str_manage'] .= '<a href="javascript:todo(\'add\',' . $r['catid'] . ',\'添加子分类\')">添加子分类</a> | ';

                $r['str_manage'] .= '<a href="javascript:todo(\'edit\',' . $r['catid'] . ',\'修改分类(catid:' . $r['catid'] . ')\')">修改</a> | <a href="javascript:del(' . $r['catid'] . ')">删除</a>';
                $r['typename'] = $this->types[$r['type']];
                $r['ismenu'] = $r['ismenu'] ? '是' : '否';

                $r['help'] = '';
                $r['url'] = "<a href='$r[url]' target='_blank'>访问</a>";
                $categorys[$r['catid']] = $r;
            }
        }

        $str = "<tr>
                    <td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
                    <td align='center'>\$id</td>
                    <td >\$spacer\$catname</td>
                    <td>\$typename</td>
                    <td>\$modelname</td>
                    <td align='center'>\$ismenu</td>
                    <td align='center'>\$url</td>
                    <td align='center' >\$str_manage</td>
                </tr>";
        $tree->init($categorys);
        $categorys = $tree->get_tree(0, $str);
        //get_tree(父ID,格式化字符串,默认选中哪个分类,修饰前缀,父级分类样式) 前面两个必填，后面三个可选
        View::assign([
            'categorys' => $categorys
        ]);
        // 模板输出
        return View::fetch('category/index');
    }

}