<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'index',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => true,

//会员，实名状态
    'dengcao_member_isrn' => array(0 => "否",1 => "是",2 => "待审"),

    //文章，状态
    'dengcao_article_status' => array(0 => "无效",1 => "在审",2 => "退稿",9 => "通过"),

    //分类，栏目类型ID
    'dengcao_category_types' => array(0 => "内部栏目",1 => "单网页",2 => "外部链接"),

    //分类，模型ID，0=系统，1=文章
    'dengcao_category_modelid' => array(0 => "系统内置",1 => "文章模型",2 => "下载模型",3 => "图片模型",4 => "视频模型"),

    //后台权限数组，开发过程中，必须把所有权限都列出来并与程序内部设定一致，以便验证。标识符必须保持唯一性，不能相同
    'dengcao_role_auths'  => array(
        //格式为：'标识符' => array('name'=>'权限名','remarks'=>'权限说明'),
        'config'  =>  array('name'=>'网站配置','remarks'=>'管理网站名称、备案号等一些配置'),
        'roles'  =>  array('name'=>'权限组管理','remarks'=>'可以增删改权限组（拥有此权限相当于超级管理员）'),
        'admin'  =>  array('name'=>'管理员管理','remarks'=>'可以增删改管理员（拥有此权限相当于超级管理员）'),
        'log_view'  =>  array('name'=>'查看系统日志','remarks'=>'可以查看系统日志'),
        'log_del'  =>  array('name'=>'删除系统日志','remarks'=>'可以删除系统日志'),
        'mine'  =>  array('name'=>'修改自己资料','remarks'=>'可以查看修改自己的资料和密码'),
        'category'  =>  array('name'=>'分类管理','remarks'=>'可以增删改分类'),
        'article'  =>  array('name'=>'文章管理','remarks'=>'可以增删改文章'),
        'member'  =>  array('name'=>'用户管理','remarks'=>'可以增删改用户'),
        'mbr_group'  =>  array('name'=>'用户组管理','remarks'=>'可以增删改用户组'),
        'comment'  =>  array('name'=>'评论管理','remarks'=>'可以管理用户发布的评论'),
    ),

    //前台用户权限，开发过程中，必须把所有权限都列出来并与程序内部设定一致，以便验证。标识符必须保持唯一性，不能相同
    'dengcao_member_role_auths'  => array(
        //格式为：'标识符' => array('name'=>'权限名','remarks'=>'权限说明'),
        'mine'  =>  array('name'=>'修改自己资料和密码','remarks'=>'可以修改自己资料和密码'),
        'cmt'  =>  array('name'=>'评论文章','remarks'=>'可以对文章发布评论'),
    ),

    //后台初始化菜单,json数据
    'dengcao_init_config'=>'
 {
  "homeInfo": {
    "title": "首页",
    "href": "'.url("admin/index/welcome").'"
  },
  "logoInfo": {
    "title": "后台管理系统",
    "image": "'.get_cz_path().'static/admin/dengcao/logo/logo.png",
    "href": ""
  },
  "menuInfo": [
    {
      "title": "常规管理",
      "icon": "fa fa-address-book",
      "href": "",
      "target": "_self",
      "child": [      
        {
          "title": "系统设置",
          "href": "'.url("admin/WebConfig/index").'",
          "icon": "fa fa-gears",
          "target": "_self"
        },
        {
          "title": "管理员",
          "href": "",
          "icon": "fa fa-user-circle",
          "target": "_self",
          "child": [
            {
              "title": "管理员",
              "href": "'.url("admin/administrators/index").'",
              "icon": "fa fa-user-circle-o",
              "target": "_self"
            },
            {
              "title": "权限组",
              "href": "'.url("admin/roles/index").'",
              "icon": "fa fa-users",
              "target": "_self"
            }
          ]
        },
        {
          "title": "分类管理",
          "href": "'.url("admin/category/index").'",
          "icon": "fa fa-th-list",
          "target": "_self"
        },
        {
          "title": "文章管理",
          "href": "'.url("admin/article/index").'",
          "icon": "fa fa-newspaper-o",
          "target": "_self"
        },
        {
          "title": "用户管理",
          "href": "",
          "icon": "fa fa-user",
          "target": "_self",
          "child": [
            {
              "title": "用户列表",
              "href": "'.url("admin/member/index").'",
              "icon": "fa fa-user-circle-o",
              "target": "_self"
            },
            {
              "title": "用户组",
              "href": "'.url("admin/MemberGroup/index").'",
              "icon": "fa fa-users",
              "target": "_self"
            }
          ]
        },
        {
          "title": "评论管理",
          "href": "",
          "icon": "fa fa-comment",
          "target": "_self",
          "child": [
            {
              "title": "评论管理",
              "href": "'.url("admin/comment/index").'",
              "icon": "fa fa-comment-o",
              "target": "_self"
            },
            {
              "title": "查看评论",
              "href": "'.url("index/comment/index").'",
              "icon": "fa fa-comments-o",
              "target": "_blank"
            }
          ]
        },
        {
          "title": "系统日志",
          "href": "",
          "icon": "fa fa-file-text",
          "target": "_self",
          "child": [
            {
              "title": "查看日志",
              "href": "'.url("admin/syslog/index").'",
              "icon": "fa fa-file-text-o",
              "target": "_self"
            }
          ]
        },
        {
          "title": "后台地图",
          "href": "'.url("admin/index/menu").'",
          "icon": "fa fa-map-signs",
          "target": "_self"
        }
      ]
    },
    {
      "title": "演示页面",
      "icon": "fa fa-lemon-o",
      "href": "",
      "target": "_self",
      "child": [
        {
          "title": "主页模板",
          "href": "",
          "icon": "fa fa-home",
          "target": "_self",
          "child": [
            {
              "title": "主页一",
              "href": "'.url("admin/demo/welcome1").'",
              "icon": "fa fa-tachometer",
              "target": "_self"
            },
            {
              "title": "主页二",
              "href": "'.url("admin/demo/welcome2").'",
              "icon": "fa fa-tachometer",
              "target": "_self"
            },
            {
              "title": "主页三",
              "href": "'.url("admin/demo/welcome3").'",
              "icon": "fa fa-tachometer",
              "target": "_self"
            }
          ]
        },
        {
          "title": "表格示例",
          "href": "'.url("admin/demo/table").'",
          "icon": "fa fa-file-text",
          "target": "_self"
        },
        {
          "title": "表单示例",
          "href": "",
          "icon": "fa fa-calendar",
          "target": "_self",
          "child": [
            {
              "title": "普通表单",
              "href": "'.url("admin/demo/form").'",
              "icon": "fa fa-list-alt",
              "target": "_self"
            },
            {
              "title": "分步表单",
              "href": "'.url("admin/demo/form_step").'",
              "icon": "fa fa-navicon",
              "target": "_self"
            }
          ]
        },
        {
          "title": "异常页面",
          "href": "",
          "icon": "fa fa-home",
          "target": "_self",
          "child": [
            {
              "title": "404页面-1",
              "href": "'.url("admin/demo/page404_1").'",
              "icon": "fa fa-hourglass-end",
              "target": "_self"
            },
            {
              "title": "404页面-2",
              "href": "'.url("admin/demo/page404_2").'",
              "icon": "fa fa-hourglass-end",
              "target": "_self"
            }
          ]
        },
        {
          "title": "其它界面",
          "href": "",
          "icon": "fa fa-snowflake-o",
          "target": "",
          "child": [
            {
              "title": "按钮示例",
              "href": "'.url("admin/demo/button").'",
              "icon": "fa fa-snowflake-o",
              "target": "_self"
            },
            {
              "title": "弹出层",
              "href": "'.url("admin/demo/layer").'",
              "icon": "fa fa-shield",
              "target": "_self"
            }
          ]
        }, {
          "title": "日期时间选择",
          "href": "'.url("admin/demo/laydate").'",
          "icon": "fa fa-calendar",
          "target": "_self"
        },
        {
          "title": "图标列表",
          "href": "'.url("admin/demo/icon").'",
          "icon": "fa fa-dot-circle-o",
          "target": "_self"
        },
        {
          "title": "图标选择",
          "href": "'.url("admin/demo/icon_picker").'",
          "icon": "fa fa-adn",
          "target": "_self"
        },
        {
          "title": "颜色选择",
          "href": "'.url("admin/demo/color_select").'",
          "icon": "fa fa-dashboard",
          "target": "_self"
        },
        {
          "title": "下拉选择",
          "href": "'.url("admin/demo/table_select").'",
          "icon": "fa fa-angle-double-down",
          "target": "_self"
        },
        {
          "title": "文件上传",
          "href": "",
          "icon": "fa fa-arrow-up",
          "target": "_self",
          "child": [
            {
              "title": "用UEditor上传",
              "href": "'.get_cz_path().'static/admin/dengcao/ueditor/demo_upload.html",
              "icon": "fa fa-snowflake-o",
              "target": "_self"
            },
            {
              "title": "zyupload",
              "href": "'.url("admin/demo/upload").'",
              "icon": "fa fa-shield",
              "target": "_self"
            }
          ]
        },
        {
          "title": "富文本编辑器",
          "href": "",
          "icon": "fa fa-edit",
          "target": "_self",
          "child": [
            {
              "title": "百度UEditor",
              "href": "'.url("admin/demo/ueditor").'",
              "icon": "fa fa-snowflake-o",
              "target": "_self"
            },
            {
              "title": "wangEditor",
              "href": "'.url("admin/demo/editor").'",
              "icon": "fa fa-shield",
              "target": "_self"
            }
          ]
        },
        {
          "title": "省市县区选择器",
          "href": "'.url("admin/demo/area").'",
          "icon": "fa fa-rocket",
          "target": "_self"
        },
        {
          "title": "多级菜单",
          "href": "",
          "icon": "fa fa-meetup",
          "target": "",
          "child": [
            {
              "title": "按钮1",
              "href": "'.url("admin/demo/button").'?v=1",
              "icon": "fa fa-calendar",
              "target": "_self",
              "child": [
                {
                  "title": "按钮2",
                  "href": "'.url("admin/demo/button").'?v=2",
                  "icon": "fa fa-snowflake-o",
                  "target": "_self",
                  "child": [
                    {
                      "title": "按钮3",
                      "href": "'.url("admin/demo/button").'?v=3",
                      "icon": "fa fa-snowflake-o",
                      "target": "_self"
                    },
                    {
                      "title": "表单4",
                      "href": "'.url("admin/demo/form").'?v=1",
                      "icon": "fa fa-calendar",
                      "target": "_self"
                    }
                  ]
                }
              ]
            }
          ]
        },
        {
          "title": "失效菜单",
          "href": "'.url("admin/demo/page404_2").'",
          "icon": "fa fa-superpowers",
          "target": "_self"
        }
      ]
    },
    {
      "title": "工具大全",
      "icon": "fa fa-slideshare",
      "href": "",
      "target": "_self",
      "child": [
        
        {
          "title": "站长工具",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "域名Whois查询",
              "href": "https://t.5300.cn/whois/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "二维码生成器",
              "href": "https://t.5300.cn/qrcode/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "二维码解码器",
              "href": "https://t.5300.cn/ewmjm/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "IP地址查询",
              "href": "https://t.5300.cn/ip/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "端口扫描器",
              "href": "https://t.5300.cn/port_scanner/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "生成htaccess",
              "href": "https://t.5300.cn/htaccess/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "htaccess转Nginx",
              "href": "https://t.5300.cn/apache2nginx/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "IPv4与IPv6互换",
              "href": "https://t.5300.cn/ipv4-ipv6/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "DNS检测",
              "href": "https://t.5300.cn/dns/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        },{
          "title": "图片处理",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "美图秀秀网页版",
              "href": "https://t.5300.cn/ps/meitu/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "PS网页版",
              "href": "https://t.5300.cn/ps/photoshop/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "ico图标制作",
              "href": "https://t.5300.cn/ico/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "中国传统色彩",
              "href": "https://t.5300.cn/zgcolor/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "日本传统色彩",
              "href": "https://t.5300.cn/rbcolor/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        },{
          "title": "代码转换",
          "href": "",
          "icon": "fa fa-wrench",
          "target": "",
          "child": [
            {
              "title": "JS混淆加密",
              "href": "https://t.5300.cn/hdsojso/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "正则测试",
              "href": "https://t.5300.cn/regex-test/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "CSS压缩/美化",
              "href": "https://t.5300.cn/cssmeihua/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "JS压缩/美化",
              "href": "https://t.5300.cn/jsmeihua/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "HTML/JS格式化",
              "href": "https://t.5300.cn/js_html/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "JSON格式化",
              "href": "https://t.5300.cn/jsonformat/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "HTML/UBB互转",
              "href": "https://t.5300.cn/html_ubb/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "图片转Base64",
              "href": "https://t.5300.cn/img2base64/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "时间戳转换",
              "href": "https://t.5300.cn/timestamp/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "URL编码解码",
              "href": "https://t.5300.cn/encodeuri/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Base64编码/解码",
              "href": "https://t.5300.cn/base64/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "MD5加密",
              "href": "https://t.5300.cn/md5/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Unicode互转",
              "href": "https://t.5300.cn/chinese2unicode/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "科学计算器",
              "href": "https://t.5300.cn/jisuanqi/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            },
            {
              "title": "Emoji表情",
              "href": "https://t.5300.cn/emoji/",
              "icon": "fa fa-angle-right",
              "target": "_blank"              
            }
          ]
        }
      ]
    }
  ]
}
',




];
