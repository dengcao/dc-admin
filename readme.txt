dc-admin（原caozha-admin） 2.x 使用手册


此文档可能不是最新，请查看网络版最新的开发手册：

码云Wiki：https://gitee.com/dengzhenhua/dc-admin/wikis
GitHub Wiki：https://github.com/dengcao/dc-admin/wiki

【快速安装】

1、安装环境：
   PHP版本：dc-admin 2.x 必须PHP8.2+以上版本（经测试支持：PHP 8.2/8.3/8.4/8.5）。
2、上传目录/Src/内所有源码到服务器，并设置网站的根目录指向目录/public/。（此为ThinkPHP8.1要求）
3、将/Database/目录里的.sql文件导入到MYSQL数据库。
4、修改文件/config/database.php，配置您的数据库信息（如果测试时启用了/.env，还需要修改文件/.env，系统会优先使用此配置文件）。
5、后台访问地址：http://您的域名/admin/index/login  （账号：dengcao   密码：123456）


**伪静态设置**

1、ThinkPHP框架必须在根目录下设置伪静态才能正常访问，否则会显示404错误。

2、如果您使用的是Apache，伪静态设置为（.htaccess）：

<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?s=$1 [QSA,PT,L]
</IfModule>

3、如果您使用的是Nginx，伪静态设置为：

location / {
    index index.php;
    if (!-e $request_filename) {
       rewrite  ^(.*)$  /index.php?s=/$1  last;
       break;
    }
}

4、在网站根目录（/public/）下，有两个文件：.htaccess和nginx.htaccess，分别是Apache和Nginx的伪静态文件，您可以直接拿来使用，在phpstudy8.1测试正常。


【开发】

准备
使用本源码之前，建议先熟悉ThinkPHP8.1.x的多应用模式和LayUI框架。
ThinkPHP 8.x 在线开发手册：https://doc.thinkphp.cn/v8_0/preface.html
ThinkPHP 8.x 离线开发手册：https://github.com/dengcao/thinkphp8  或  https://gitee.com/dengzhenhua/thinkphp8
LayUI 2.x 开发手册：http://layui.dev/docs

正式
一、数据库配置
打开/config/database.php，设置数据库各项。
注意：此为全局配置，是对所有应用起效的，如需要只对应用admin单独起效，则将此文件移动到/app/admin/config/即可。

二、应用配置
配置文件：/app/admin/config/目录下所有文件均为配置文件。其中app.php是应用的基础配置文件。

三、开发

1、后台权限的配置
打开Src/app/admin/config/app.php，找到项“dengcao_role_auths”，如下：

'dengcao_role_auths' => array(

    //格式为：'标识符' => array('name'=>'权限名','remarks'=>'权限说明'),

    'config'  =>  array('name'=>'网站配置','remarks'=>'管理网站名称、备案号等一些配置'),

    'roles'  =>  array('name'=>'权限组管理','remarks'=>'可以增删改权限组（拥有此权限相当于超级管理员）'),

    'admin'  =>  array('name'=>'管理员管理','remarks'=>'可以增删改管理员（拥有此权限相当于超级管理员）'),

    'log_view'  =>  array('name'=>'查看系统日志','remarks'=>'可以查看系统日志'),

    'log_del'  =>  array('name'=>'删除系统日志','remarks'=>'可以删除系统日志'),

    'mine'  =>  array('name'=>'修改自己资料','remarks'=>'可以查看修改自己的资料和密码'),

    'article'  =>  array('name'=>'文章管理','remarks'=>'可以增删改文章'),
),

如上，在开发过程中，必须把所有权限都用数组的方式列出来并与程序内部设定一致，以便验证。
标识符是用来判定权限的依据，必须保持唯一性，不能相同。



2、权限在控制器中的应用


引入控制器中间件：

protected $middleware = [

    'dengcao_auth' 	=> ['except' => '' ],//验证是否管理员

];

此中间件对该控制器下所有方法都起效，是判断是否后台管理员的。这个适合后台所有页面，只要是管理员就能查看。



通过构造函数判断当前登录账户是否拥有操作该控制器的权限：

public function __construct(){

    dc_auth("admin");//检测是否有权限

}
此函数对该控制器下的所有方法都起效。其中，admin是权限标识符，是与刚才app.php里匹配的。



在控制器方法内部使用：

dc_auth("admin"); //admin是权限标识符

如果没有权限，自动终止程序的运行并报错。



判断是否拥有某个标识符的权限：

if(is_dc_auth("log_del")==false){

        return json(array("code"=>0,"del_num"=>0,"msg"=>"删除失败，您没有删除系统日志的权限"));

    }

其中log_del是权限标识符。



3、写入系统日志

支持在任何地方记录系统日志，方法：

write_syslog($array)

其中$array为数组，格式：array("log_content"=>"","log_user"=>"","log_ip"=>"","log_datetime"=>"")，除log_content必填外其他可省略。省略时会自动获取当前登录用户的信息填入。



示例：

write_syslog(array("log_content"=>"删除系统日志"));//记录系统日志




4、无限级别的分类系统

打开应用配置文件：app/admin/config/app.php，找到：

//分类，栏目类型ID
'dengcao_category_types' => array(0 => "内部栏目",1 => "单网页",2 => "外部链接"),
//分类，模型ID，0=系统，1=文章`
'dengcao_category_modelid' => array(0 => "系统内置",1 => "文章模型",2 => "下载模型",3 => "图片模型",4 => "视频模型"),

此处可以配置分类的类型，模型。对应数据库表为：dc_category。

另外，有一点值得说明的是，在控制器app\admin\controller\Category.php下，有一个方法：delete_category_content($catid, $modelid)，需要在您开发的时候加上删除文章、图片等数据的代码（如果有的话）。



5、文章管理系统

打开应用配置文件：app/admin/config/app.php，找到：

//文章，状态`
'dengcao_article_status' => array(0 => "无效",1 => "在审",2 => "退稿",9 => "通过"),`

此处可以配置文章审核状态，会在全局起作用。

注意： 文章管理系统已与无限分类系统关联好，开箱即用。文章管理系统的主要代码全部集中在控制器：app\admin\controller\Article.php，可根据需要做实际的修改。



6、用户（会员）管理系统

打开应用配置文件：app/admin/config/app.php，找到：

//会员，实名状态
'dengcao_member_isrn' => array(0 => "否",1 => "是",2 => "待审"),

此处可以设置会员的实名状态。

重要说明：

为了提高兼容性，用户（会员）系统参考了PHPCMS等系统的会员数据库架构。利用此会员系统，可以很方便的架构各种各样的会员体系。自带了用户组功能，可以无限拓展用户组（等级）以及分配用户权限。



7、评论系统

插入评论：

<script>
        dc_cmt_template="/index/comment/template.html";//模板页接口
        dc_cmt_list="/index/comment/list.html";//读取评论列表接口
        dc_cmt_doaction="/index/comment/doaction.html";//发布评论接口
        dc_cmt_dolike="/index/comment/dolike.html";//点赞或踩接口
        dc_cmt_userinfo="/index/comment/userinfo.html";//获取会员信息
    </script>
    <div class="pinglun">
        <div class="pl-520am" data-cmtid="act_1" data-catid="0" data-pagesize="5" data-scrollload="1" data-scrollbottom="50" data-showhot="3" data-hotpagesize="3"></div>
        <script type="text/javascript" src="/static/index/dc_cmt/api.js"></script>
    </div>

上面的data-cmtid是评论标识符ID，data-catid是评论标识符分类ID，这两个参数是用来区分文章等评论的，一般情况下使用data-cmtid就足够了。

评论参数设置：

打开控制器 app\index\controller\comment.php，找到$cmt_config，可以设置评论每页的数量、验证码、缓存、是否需要审核、是否允许发图片、滚动自动加载、屏蔽词等等。


8、修改后台默认界面

打开public/static/admin/dengcao/js/all.js文件，将代码：

var layuimini_bgColorDefault=3;

中的数字3修改为：0至11之间，一共有12套主题模板可以选择。


更多使用方法，请参考源码内的示例。