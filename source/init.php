<?php
/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 2015/8/14
 * Time: 16:11
 */

header("content-type:text/html;charset=utf-8");

date_default_timezone_set("PRC");

if(!isset($_SESSION)){
    session_start();
}

define("ROOT",dirname(__FILE__));

//定义包含路径
//set_include_path(".".PATH_SEPARATOR.ROOT."/includes".PATH_SEPARATOR.ROOT."/config".PATH_SEPARATOR.get_include_path());


// 载入配置
if ((!@include_once './config/db.config.php')||(!@include_once './config/oauth.config.php')) {
    file_exists('./install.php') ? header('Location: install.php') : print('Missing Config File');
    exit;
}


//载入配置
require_once "./config/db.config.php";
require_once "./config/oauth.config.php";
//载入类
require_once "./includes/db.class.php";
require_once "./includes/db_function.class.php";
require_once "./includes/oauth.class.php";
require_once "./includes/user.class.php";

