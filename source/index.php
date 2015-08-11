<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-11
 * Time: 下午3:28
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


/*中间的显示部分*/
function get_proper_question()
{
    $db_function = new db_sql_functions();  //获取数据库连接
    $i = 0;
    do {
        array_push($result,$db_function->get_question_list($i , 20) );
        foreach ($result as $temp) {
            if ((($db_function->get_question_status($temp['qid']) >> 1) & 1) == 1) {
                array_slice($result, $temp, 1);
            }
        }
        $i += 20;
    } while (count($result) <= 20);
    array_slice($result ,0, 20);
    return $result;
}

$quesion_list = get_proper_question();    //question_list 获得的是前端可以展示的20个问题的列表




