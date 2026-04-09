<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

// 应用公共文件

//if (version_compare(PHP_VERSION,"8.1.0",">=")) {error_reporting(0);} //解决php8.1时的错误问题

use think\facade\Request;

/**
 * 过滤参数，防SQL注入
 * @param string $str 接受的参数
 * @return string
 */
function filter_sql($str)
{
    $farr = array(
        //"/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
        "/select|insert|update|delete/is"
    );
    $str = preg_replace($farr, '', $str);
    return trim(addslashes($str));
}

/**
 * 过滤接受的参数或者数组,如$_GET,$_POST
 * @param array|string $arr 接受的参数或者数组
 * @return array|string
 */
function filter_sql_arr($arr)
{
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $arr[$k] = filter_sql($v);
        }
    } else {
        $arr = filter_sql($arr);
    }
    return $arr;
}

/**
 * 获取客户端IP
 * @return string
 */
function getip() { //获取客户端IP
    if ( isset($_SERVER[ "HTTP_CDN_SRC_IP" ]) ) { //获取网宿CDN真实客户IP
        return replace_ip( $_SERVER[ "HTTP_CDN_SRC_IP" ] );
    }
    if ( isset($_SERVER[ "HTTP_X_FORWARDED_FOR" ]) ) { //获取网宿、阿里云真实客户IP，参考：https://help.aliyun.com/knowledge_detail/40535.html
        return replace_ip( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] );
    }
    if ( isset($_SERVER[ "HTTP_CLIENT_IP" ]) ) {
        return $_SERVER[ "HTTP_CLIENT_IP" ];
    }
    if ( isset($_SERVER[ "HTTP_X_FORWARDED" ]) ) {
        return $_SERVER[ "HTTP_X_FORWARDED" ];
    }
    if ( isset($_SERVER[ "HTTP_FORWARDED_FOR" ]) ) {
        return $_SERVER[ "HTTP_FORWARDED_FOR" ];
    }
    if ( isset($_SERVER[ "HTTP_FORWARDED" ]) ) {
        return $_SERVER[ "HTTP_FORWARDED" ];
    }
    $httpip = $_SERVER[ 'REMOTE_ADDR' ];
    if ( !preg_match( "/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/", $httpip ) ) {
        $httpip = "127.0.0.1";
    }
    return $httpip;
}

/**
 * 拆分代理IP
 * @return string
 */
function replace_ip($ip)
{

    if (!$ip) {
        return "";
    }

    $httpip_array = explode(",", $ip);

    if ($httpip_array[0]) {

        return $httpip_array[0];

    } else {

        return $ip;

    }

}