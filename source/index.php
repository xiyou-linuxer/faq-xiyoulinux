<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-11
 * Time: 下午3:28
 */

require_once "init.php";
require_once "includes/show.function.php";

$user = new user();

/*右上角用户信息部分*/
$user_info= json_decode($user->user_get_login(),true); //获得用户登录的json,包含了用户的所有信息
$is_login = $user_info['ret'];   //is_login 用来标记用户是否登录,如果用户登录,赋值为0, 如果用户没有登录,赋值为非0



/*左侧标签部分*/
$left_tags = get_left_tags();    //left_tags 存储的是左侧标签的数组

/*右侧推荐部分*/
$right_rec = get_right_rec();     //right_rec 存储的是右侧推荐的集合


/*中间的显示部分*/
function get_proper_question()
{
    $db_function = new db_sql_functions();  //获取数据库连接
    $i = 0;
    $result = array();
    do{
        $get = $db_function->get_question_list($i, 20);
        if($get != null) {
            foreach ($get as $key=>$question) {
                if ((((int)$db_function->get_question_status($question['qid'])) & 1) == 1) {
                    unset($get[$key]);

                }
            }
            $result = array_merge($result, $get);
            $i += 20;
        } else {
            break;
        }
    } while (count($result) <= 20) ;
    $result = array_slice($result ,0, 20);
    return $result;
}

$quesion_list = get_proper_question();    //question_list 获得的是前端可以展示的20个问题的列表

/*var_dump($is_login);
var_dump($user_info);
var_dump($left_tags);
var_dump($right_rec);
var_dump($quesion_list);*/
