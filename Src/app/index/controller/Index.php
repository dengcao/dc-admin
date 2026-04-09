<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

namespace app\index\controller;

class Index
{
    public function index()
    {
       return '<title>欢迎使用'.get_cz_name().' '.get_cz_version().'</title><style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:14px;} h1{ font-size: 32px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 18px }</style><div style="padding: 24px 48px;"> <h1>:) 欢迎使用</h1><p><br>'.get_cz_name().' '.get_cz_version().'<br><br>'.'后台管理地址：<a href="http://'.$_SERVER['HTTP_HOST'].url("admin/index/index").'" target="_blank">http://'.$_SERVER['HTTP_HOST'].url("admin/index/index").'</a> （账号：dengcao &nbsp; 密码：123456）<br><br>邓草官网：<a href="http://blog.5300.cn" target="_blank">5300.cn</a><br><br>GitHub：<a href="https://github.com/'.$GLOBALS["dengcao_common_config"]["github"].'" target="_blank">https://github.com/'.$GLOBALS["dengcao_common_config"]["github"].'</a><br>Gitee：<a href="https://gitee.com/'.$GLOBALS["dengcao_common_config"]["gitee"].'" target="_blank">https://gitee.com/'.$GLOBALS["dengcao_common_config"]["gitee"].'</a><br><br><a href="'.url("index/comment/index").'"  target="_blank" style="color:red;font-weight: bold;">查看评论演示</a></p></div>';
    }

}
