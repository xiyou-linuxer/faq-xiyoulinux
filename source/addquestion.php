<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-11
 * Time: 下午6:57
 */

require_once ('./includes/db_function.class.php');
require_once  ('./includes/user.class.php');
require_once('./includes/show.function.php');


/*右上角用户信息部分*/
$user_is_login = get_user_status();    //user_is_login 为0表示已经登录,否则表示没有登录
$user_info = get_user_info();     //user_info存储用户的相关信息,是一个array


/*左侧标签部分*/
$left_tags = get_left_tags();    //left_tags 存储的是左侧标签的数组


/*右侧推荐部分*/
$right_rec = get_right_rec();     //right_rec 存储的是右侧推荐的集合


