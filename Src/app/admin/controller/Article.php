<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

namespace app\admin\controller;

use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use app\admin\model\Article as ArticleModel;

class Article
{
    protected $middleware = [
        'dengcao_auth' 	=> ['except' => '' ],//验证是否管理员
    ];

    public function __construct(){
        dc_auth("article");//检测是否有权限
    }

    public function index()
    {
        $web_config=get_web_config();
        $limit=$web_config["article_limit"];
        if(!is_numeric($limit)){
            $limit=15;//默认显示15条
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
        $source_string .= $tree->get_tree(0, $str);

        $article_status = Config::get("app.dengcao_article_status");

        View::assign([
            'article_limit'  => $limit,
            'article_status' => $article_status,
            'parentid_select' => $source_string,
        ]);
        // 模板输出
        return View::fetch('article/index');
    }

    public function add()
    {
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
        $source_string .= $tree->get_tree(0, $str);

        $article_status = Config::get("app.dengcao_article_status");

        View::assign([
            'article_status' => $article_status,
            'parentid_select' => $source_string,
        ]);
        // 模板输出
        return View::fetch('article/add');
    }

    public function addSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $update_data=Request::param('','','filter_sql');//过滤注入
        $update_data["islink"]=isset($update_data["islink"])?$update_data["islink"]:0;
        $update_data["iscomment"]=isset($update_data["iscomment"])?$update_data["iscomment"]:0;
        $update_data["isreco"]=isset($update_data["isreco"])?$update_data["isreco"]:0;
        $update_data["ishot"]=isset($update_data["ishot"])?$update_data["ishot"]:0;
        $update_data["istop"]=isset($update_data["istop"])?$update_data["istop"]:0;

        $aid = Db::name('article')->insertGetId($update_data);

        if($aid>0){
            write_syslog(array("log_content"=>"新增文章，ID：".$aid));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"添加成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"添加失败");
        }
        return json($list);
    }

    public function edit()
    {
        $aid=Request::param("aid",'','filter_sql');
        if(!is_numeric($aid)){
            dengcao_error("参数错误","",1);
        }
        $article=ArticleModel::where("aid","=",$aid)->findOrEmpty();
        if ($article->isEmpty()) {
            dengcao_error("[ID:".$aid."]文章不存在。","",1);
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
        $source_string .= $tree->get_tree(0, $str, $article->catid);

        $article_status = Config::get("app.dengcao_article_status");

        View::assign([
            'article_status' => $article_status,
            'article'  => $article,
            'parentid_select' => $source_string,
        ]);

        // 模板输出
        return View::fetch('article/edit');
    }

    public function editSave()
    {
        if(!Request::isAjax()){
            // 如果不是AJAX
            return result_json(0,"error");
        }
        $update_data=Request::param('','','filter_sql');//过滤注入
        if(!is_numeric($update_data["aid"])){
            dengcao_error("参数错误","",1);
        }

        $update_data["islink"]=isset($update_data["islink"])?$update_data["islink"]:0;
        $update_data["iscomment"]=isset($update_data["iscomment"])?$update_data["iscomment"]:0;
        $update_data["isreco"]=isset($update_data["isreco"])?$update_data["isreco"]:0;
        $update_data["ishot"]=isset($update_data["ishot"])?$update_data["ishot"]:0;
        $update_data["istop"]=isset($update_data["istop"])?$update_data["istop"]:0;

        $update_field=['catid','title','content','style','thumb','keywords','description','tags','url','listorder','status','islink','inputtime','iscomment','author','copyfrom','hits','isreco','ishot','istop'];//允许更新的字段
        $article=ArticleModel::where("aid","=",$update_data["aid"])->findOrEmpty();
        if ($article->isEmpty()) {//数据不存在
            $update_result=false;
        }else{
            $update_result=$article->allowField($update_field)->save($update_data);
        }

        if($update_result){
            write_syslog(array("log_content"=>"修改文章，ID：".$update_data["aid"]));//记录系统日志
            $list=array("code"=>1,"update_num"=>1,"msg"=>"保存成功");
        }else{
            $list=array("code"=>0,"update_num"=>0,"msg"=>"保存失败");
        }
        return json($list);
    }

    public function view()
    {
        $aid=Request::param("aid",'','filter_sql');
        if(!is_numeric($aid)){
            dengcao_error("参数错误","",1);
        }
        $article=ArticleModel::where("aid","=",$aid)->with('category')->withAttr('status', function($value) {
            //$status = [0=>'无效',1=>'在审',2=>'<font color="red">退稿</font>,9=>\'<font color="green">通过</font>\''];
            $status = Config::get("app.dengcao_article_status");
            return $status[$value];
        })->withAttr('islink', function($value) {
            $islink = [0=>'否',1=>'是'];
            return $islink[$value];
        })->withAttr('iscomment', function($value) {
            $iscomment = [0=>'否',1=>'是'];
            return $iscomment[$value];
        })->withAttr('isreco', function($value) {
            $islink = [0=>'否',1=>'是'];
            return $islink[$value];
        })->withAttr('ishot', function($value) {
            $iscomment = [0=>'否',1=>'是'];
            return $iscomment[$value];
        })->withAttr('istop', function($value) {
            $islink = [0=>'否',1=>'是'];
            return $islink[$value];
        })->findOrEmpty();
        if ($article->isEmpty()) {
            dengcao_error("[ID:".$aid."]文章不存在。","",1);
        }else{
            View::assign([
                'article'  => $article
            ]);
        }
        // 模板输出
        return View::fetch('article/view');
    }

    public function get()//获取文章数据
    {
        $page=Request::param("page");
        if(!is_numeric($page)){$page=1;}
        $limit=Request::param("limit");
        if(!is_numeric($limit)){
            $web_config=get_web_config();
            $limit=$web_config["article_limit"];
            if(!is_numeric($limit)){
                $limit=15;//默认显示15条
            }
        }

        $list=Db::name('article');
        $list=$list->alias('a')->leftJoin('category c','a.catid = c.catid');
        $list=$list->alias('a')->field('a.*,c.catname');
        $list=$list->withAttr('status', function($value) {
            $status = Config::get("app.dengcao_article_status");
            return $status[$value];
        })->withAttr('islink', function($value) {
            $islink = [0=>'否',1=>'是'];
            return $islink[$value];
        })->withAttr('iscomment', function($value) {
            $iscomment = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $iscomment[$value];
        })->withAttr('isreco', function($value) {
            $islink = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $islink[$value];
        })->withAttr('ishot', function($value) {
            $iscomment = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $iscomment[$value];
        })->withAttr('istop', function($value) {
            $islink = [0=>'<i class="layui-icon layui-icon-close hese"></i>',1=>'<i class="layui-icon layui-icon-ok olivedrab"></i>'];
            return $islink[$value];
        })->order('a.aid', 'desc');

        $action=Request::param('','','filter_sql');//过滤注入
        if(isset($action["keyword"])){
            if($action["keyword"]!=""){
                $list=$list->where("a.title|a.content|a.keywords|a.description|a.url|a.copyfrom","like","%".$action["keyword"]."%");
            }
        }
        $action_arr=['catid','status','islink','iscomment','isreco','ishot','istop'];
        foreach ($action_arr as $value){
            if(isset($action[$value])){
                if($action[$value]!="") {
                    $list = $list->where("a.".$value, "=", $action[$value]);
                }
            }
        }

        $list=$list->paginate([
            'list_rows'=> $limit,//每页数量
            'page' => $page,//当前页
        ]);
        //print_r(Db::getLastSql());
        return json($list);
    }

    public function delete()//删除数据
    {
        //执行删除
        $aid=Request::param("aid",'','filter_sql');
        $del_num=0;
        if($aid){
            $del_num=ArticleModel::where("aid","in",$aid)->delete();
        }
        if($del_num>0){
            write_syslog(array("log_content"=>"删除文章(ID)：".$aid));//记录系统日志
            $list=array("code"=>1,"del_num"=>$del_num);
        }else{
            $list=array("code"=>0,"del_num"=>0);
        }

        return json($list);
    }

}
