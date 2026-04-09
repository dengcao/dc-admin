<?php
/**
 * 源码名：dc-admin
 * Copyright © 邓草 （官网：http://blog.5300.cn）
 * 基于木兰宽松许可证 2.0（Mulan PSL v2）免费开源，您可以自由复制、修改、分发或用于商业用途，但需保留作者版权等声明。详见开源协议：http://license.coscl.org.cn/MulanPSL2
 * dc-admin (Software Name) is licensed under Mulan PSL v2. Please refer to: http://license.coscl.org.cn/MulanPSL2
 * Github：https://github.com/dengcao/dc-admin   or   Gitee：https://gitee.com/dengzhenhua/dc-admin
 */

// 应用公共文件

/**
 * 获取评论的表情包
 * @return array
 */
function comment_face(){
    $cmt_faces = [
        ["微笑", "wx.gif"],
        ["鄙视", "bs.gif"],
        ["闭嘴", "bz.gif"],
        ["吃惊", "cj.gif"],
        ["酷", "cool.gif"],
        ["呲牙", "cy.gif"],
        ["鼓掌", "gz.gif"],
        ["流汗", "han.gif"],
        ["哈欠", "hq.gif"],
        ["害羞", "hx.gif"],
        ["可爱", "ka.gif"],
        ["泪", "lei.gif"],
        ["难过", "ng.gif"],
        //["高手", "q.gif"],
        ["示爱", "sa.gif"],
        ["衰", "shuai.gif"],
        ["憨笑", "hanx.gif"],
        ["吐血", "tux.gif"],
        ["偷笑", "tx.gif"],
        ["斜眼笑", "xyx.gif"],
        ["笑哭", "xk.gif"],
        ["色", "se.gif"],
        ["晕", "y.gif"],
        ["折磨", "zm.gif"],
        ["坏笑", "67.gif"],
        ["撇嘴", "2.gif"],
        ["睡", "8.gif"],
        ["尴尬", "10.gif"],
        ["发怒", "11.gif"],
        ["调皮", "12.gif"],
        ["吐", "18.gif"],
        ["白眼", "21.gif"],
        ["困", "24.gif"],
        ["惊恐", "25.gif"],
        ["大兵", "28.gif"],
        ["奋斗", "29.gif"],
        ["疑问", "30.gif"],
        ["嘘", "31.gif"],
        ["敲打", "35.gif"],
        ["再见", "36.gif"],
        ["猪头", "40.gif"],
        ["抱抱", "41.gif"],
        ["蛋糕", "42.gif"],
        //["闪电", "43.gif"],
        ["炸弹", "44.gif"],
        //["刀", "45.gif"],
        ["便便", "47.gif"],
        ["咖啡", "48.gif"],
        ["饭", "49.gif"],
        ["玫瑰", "50.gif"],
        ["凋谢", "51.gif"],
        ["爱心", "52.gif"],
        ["心碎", "53.gif"],
        //["太阳", "55.gif"],
        //["月亮", "56.gif"],
        ["强", "57.gif"],
        ["弱", "58.gif"],
        ["握手", "59.gif"],
        ["抠鼻", "64.gif"],
        ["委屈", "72.gif"],
        ["阴险", "74.gif"],
        ["亲亲", "75.gif"],
        ["可怜", "77.gif"],
        ["菜刀", "78.gif"],
        ["啤酒", "79.gif"],
        ["抱拳", "84.gif"],
        ["勾引", "85.gif"],
        //["OK", "90.gif"],
        ["蜡烛", "102.gif"],
        ["鞭炮", "126.gif"],
        ["红包", "105.gif"],
        ["福", "125.gif"],
    ];
    return $cmt_faces;
}

//应用的名称及版本
$GLOBALS["dengcao_common_config"] = [
    "name" => "dc-admin",
    "version" => "2.0",
    "gitee" => "dengzhenhua/dc-admin",
    "github" => "dengcao/dc-admin",
];

//dc-admin 程序名称及版本，用于标识和升级，勿删改
$GLOBALS["dengcao_admin_sys"] = array(
    "name" => "dc-admin",
    "version" => "2.0",
    "url" => "https://gitee.com/dengzhenhua/dc-admin",
);

/**
 * 获取应用入口之前的目录，格式如：/public/或/
 * @return string
 */
function get_cz_path(){
    //$cz_path=substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], '/')+1);
    $cz_path=substr($_SERVER['PHP_SELF'],0,8);
    if($cz_path=="/public/"){return $cz_path;}else{return "/";}
}

/**
 * 获取系统应用的名字
 * @return string
 */
function get_cz_name(){
    global $dengcao_common_config;
    return $dengcao_common_config["name"];
}

/**
 * 获取系统应用的版本号
 * @return string
 */
function get_cz_version(){
    global $dengcao_common_config;
    return $dengcao_common_config["version"];
}
