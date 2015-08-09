<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-7
 * Time: 下午10:46
 *给首页提供数据的页面
 */

require_once './db_function.class.php';
require_once './oauth.class.php';
require_once './user.class.php';

/*给首页提供所有的标签,函数返回所有标签的构成的数组,参数为空
初步暂定把所有的标签是固定的,返回标签的数组*/
function get_all_tags() {
    return  array("C/C++", "Java", "PHP","Linux", "网络", "云计算", "大数据", "其它");

}

/*给首页提供前20条问题的所有属性的集合*/
function get_show_questions() {
    return get_question_list();     //调用底层数据库的接口

}

/*给首页提供用户的头像等一系列的信息*/
function get_user_information() {
    return user_getinfo();                  //调用user类的方法,直接返回用户所有信息的Json串

}

/*给首页提供精彩推荐的信息,初步暂定为一个链接,返回小组官网的链接*/
function get_recommended_information() {
    return "http://www.xiyoulinux.org";
}

function question_can_read($question_id)
{
    $status = get_question_status($question_id);       //根据问题的id获取问题的状态
    if ((($status >> 1) && 1) == 1) return false;       //获取状态的第二位数字,如果是1,表示问题被删除,不可访问.
    return true;

}


?>
