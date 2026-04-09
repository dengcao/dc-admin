<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

namespace app\admin\controller;

use app\admin\model\Comment as CommentModel;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Comment
{
    protected $cmt_config = [
        "is_br" => true,//评论内容是否转换换行符
    ];

    protected $cmt_faces=array();
    protected $cmt_url;//评论插件根URL

    protected $middleware = [
        'dengcao_auth' => ['except' => ''],//验证是否管理员
    ];

    public function __construct()
    {
        dc_auth("comment");//检测是否有权限
        $this->cmt_url=get_cz_path()."static/index/dc_cmt/";
        $this->cmt_faces = comment_face();
    }

    public function index()
    {
        $web_config = get_web_config();
        $limit = $web_config["comment_limit"];
        if (!is_numeric($limit)) {
            $limit = 15;//默认显示15条
        }
        View::assign([
            'comment_limit' => $limit,
        ]);
        // 模板输出
        return View::fetch('comment/index');
    }


    public function view()
    {
        $id = Request::param("id", '', 'filter_sql');
        if (!is_numeric($id)) {
            dengcao_error("参数错误", "", 1);
        }
        $comment = CommentModel::where("id", "=", $id)->withAttr('ischeck', function ($value) {
            $ischeck = [0 => '未审', 1 => '已审'];
            return $ischeck[$value];
        })->withAttr('ishot', function ($value) {
            $ishot = [0 => '否', 1 => '<font color="red">是</font>'];
            return $ishot[$value];
        })->withAttr('userid', function ($value) {
            if ($value == 0) {
                return "游客";
            } else {
                return $value;
            }
        })->withAttr('cmtname', function ($value) {
            if (!$value) {
                return "匿名";
            } else {
                return $value;
            }
        })->findOrEmpty();
        if ($comment->isEmpty()) {
            dengcao_error("[ID:" . $id . "]评论不存在。", "", 1);
        } else {


            $saytext = trim($comment->cmtcontent);
            if($this->cmt_config['is_br']){
                $saytext = nl2br($saytext);
            }
            $arrparentid = trim($comment->arrparentid);

            $parent_context = "";//初始化，获取父评论的内容
            if ($arrparentid) {
                $parentid_arr = explode(",", $arrparentid);
                foreach ($parentid_arr as $p_value) {
                    $p_comment = CommentModel::where('id', '=', $p_value)->findOrEmpty();
                    if ($p_comment->isEmpty()) {
                        $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @：</span><div class="ecommenttext">' . $parent_context . '（该评论已被删除）</div></div>';
                    } else {
                        if ($p_comment->ischeck == 1) {
                            $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @' . $p_comment->cmtname . '：</span><div class="ecommenttext">' . $parent_context . $p_comment->cmtcontent . '</div></div>';
                        } else {
                            $parent_context = '<div class="ecomment"><span class="ecommentauthor">引用 @' . $p_comment->cmtname . '：</span><div class="ecommenttext">' . $parent_context . $p_comment->cmtcontent . '（该评论正在审核中）</div></div>';
                        }

                    }
                }
            }
            $saytext = $this->face_replace($parent_context . $saytext);//解析表情
            $comment->cmtcontent = '<link rel="stylesheet" href="'.$this->cmt_url.'assets/style.css">'.$this->img_replace($saytext);//解析图片

            View::assign([
                'comment' => $comment
            ]);

        }
        // 模板输出
        return View::fetch('comment/view');
    }

    public function get()//获取评论数据
    {
        $page = Request::param("page");
        if (!is_numeric($page)) {
            $page = 1;
        }
        $limit = Request::param("limit");
        if (!is_numeric($limit)) {
            $web_config = get_web_config();
            $limit = $web_config["comment_limit"];
            if (!is_numeric($limit)) {
                $limit = 15;//默认显示15条
            }
        }

        $list = Db::name('comment');
        $list = $list->withAttr('ischeck', function ($value) {
            $ischeck = [0 => '未审', 1 => '已审'];
            return $ischeck[$value];
        })->withAttr('ishot', function ($value) {
            $ishot = [0 => '否', 1 => '<font color="red">是</font>'];
            return $ishot[$value];
        })->withAttr('userid', function ($value) {
            if ($value == 0) {
                return "游客";
            } else {
                return $value;
            }
        })->withAttr('cmtname', function ($value) {
            if (!$value) {
                return "匿名";
            } else {
                return $value;
            }
        })->order('addtime', 'desc');

        $action = Request::param('', '', 'filter_sql');//过滤注入
        if (isset($action["keyword"])) {
            if ($action["keyword"] != "") {
                $list = $list->where("cmtname|userid|cmtip|cmtid|catid|cmtcontent", "like", "%" . $action["keyword"] . "%");
            }
        }
        $action_arr = ['ischeck', 'ishot'];
        foreach ($action_arr as $value) {
            if (isset($action[$value])) {
                if ($action[$value] != "") {
                    $list = $list->where($value, "=", $action[$value]);
                }
            }
        }

        if (isset($action["usertype"])) {
            if ($action["usertype"] == "1") {
                $list = $list->where("userid", ">", 0);
            }elseif ($action["usertype"] == "0") {
                $list = $list->where("userid", "=", 0);
            }
        }

        $list = $list->paginate([
            'list_rows' => $limit,//每页数量
            'page' => $page,//当前页
        ]);
        //print_r(Db::getLastSql());
        return json($list);
    }

    public function todo()//操作数据
    {
        $id = Request::param("id", '', 'filter_sql');
        $act = Request::param("act", '', 'filter_sql');
        if($act=="del"){
            //删除
            $del_num = 0;
            if ($id) {
                $del_num = CommentModel::where("id", "in", $id)->delete();
            }
            if ($del_num > 0) {
                write_syslog(array("log_content" => "删除评论(ID)：" . $id));//记录系统日志
                $list = array("code" => 1, "msg"=>"删除成功，共删除了 <font color='red'>".$del_num."</font> 条评论！");
            } else {
                $list = array("code" => 0, "msg"=>"删除失败！");
            }
        }elseif($act=="check"){
            //批量审核
            $do_num = 0;
            if ($id) {
                $do_num = Db::name('comment')->where('id', "in", $id)->update(['ischeck' => 1]);
            }
            if ($do_num > 0) {
                write_syslog(array("log_content" => "通过评论审核(ID)：" . $id));//记录系统日志
            }
            $list = array("code" => 1, "msg"=>"批量审核完成，共更新了 <font color='red'>".$do_num."</font> 条评论！");
        }elseif($act=="uncheck"){
            //批量取消审核
            $do_num = 0;
            if ($id) {
                $do_num = Db::name('comment')->where('id', "in", $id)->update(['ischeck' => 0]);
            }
            if ($do_num > 0) {
                write_syslog(array("log_content" => "取消评论审核(ID)：" . $id));//记录系统日志
            }
            $list = array("code" => 1, "msg"=>"批量取消审核完成，共更新了 <font color='red'>".$do_num."</font> 条评论！");
        }elseif($act=="hot"){
            //批量设为热门
            $do_num = 0;
            if ($id) {
                $do_num = Db::name('comment')->where('id', "in", $id)->update(['ishot' => 1]);
            }
            if ($do_num > 0) {
                write_syslog(array("log_content" => "设为热门评论(ID)：" . $id));//记录系统日志
            }
            $list = array("code" => 1, "msg"=>"批量设为热门评论完成，共更新了 <font color='red'>".$do_num."</font> 条评论！");
        }elseif($act=="unhot"){
            //批量取消热门
            $do_num = 0;
            if ($id) {
                $do_num = Db::name('comment')->where('id', "in", $id)->update(['ishot' => 0]);
            }
            if ($do_num > 0) {
                write_syslog(array("log_content" => "取消热门评论(ID)：" . $id));//记录系统日志
            }
            $list = array("code" => 1, "msg"=>"批量取消热门评论完成，共更新了 <font color='red'>".$do_num."</font> 条评论！");
        }


        return json($list);
    }


    public function face_replace($saytext)
    {//替换表情标签
        if ($saytext) {
            preg_match_all("/\[\/(.*?)\]/isu", $saytext, $matches, PREG_PATTERN_ORDER);
            foreach ($matches[1] as $faces) {
                $view_face = $this->view_face($faces);
                if (count($view_face) > 0) {
                    $face_tag = "[/" . $view_face[0] . "]";
                    $saytext = str_ireplace($face_tag, "<img src=\"" . $this->cmt_url . "face/" . $view_face[1] . "\" border=\"0\">", $saytext);
                }
            }
        }
        return $saytext;
    }

    //解释图片标签
    public function img_replace($text)
    {
        $preg_str = "/\[img\](.+?)\[\/img\]/isu";
        $text = preg_replace($preg_str, "<img src=$1 class='pic' onclick=window.open('$1')>", $text);
        return $text;
    }

    public function view_face($str)
    {//获取对应的表情数组
        $cmt_faces = $this->cmt_faces;
        foreach ($cmt_faces as $faces) {
            if (trim($faces[0]) == trim($str)) {
                return $faces;
            }
        }
        return array();
    }

}
