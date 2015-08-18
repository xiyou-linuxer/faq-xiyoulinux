<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-11
 * Time: 下午5:32
 */


/*根据各个页面的相同的部分抽象出来的共同的函数*/

/*用户信息部分*/
/*判断用户状态*/

function get_user_status()   //函数返回值为0,说明用户已经登录,否则没有登录
{

    $user = new user();
    $temp = json_decode($user->user_get_login()); //获得用户登录的json
    $is_login = $temp['ret'];   //is_login 用来标记用户是否登录,如果用户登录,赋值为0, 如果用户没有登录,赋值为非0

    return $is_login;
}


/*获取用户信息*/
function get_user_info()    //如果用户已经登录,返回用用户的信息,如果没有登录,返回NULL
{
    $user = new user();
    $temp = json_decode($user->user_get_login());
    return $temp;
}


/*左侧标签部分*/
/*初步定位以下几个标签,后期会根据实际情况从数据库中获得访问量搞的标签的集合*/
function get_left_tags()      //返回左侧标签的列表
{
    $tags = array("编程语言", "WEB应用开发", "移动应用开发", "云计算", "大数据", "数据挖掘", "其它");
    return $tags;
}



/*右侧推荐部分*/
/*初步定位几个相应的超链接*/

function get_right_rec()    //返回右侧推荐的列表
{
    $rec = array("西邮linux兴趣小组" => "http://www.xiyoulinux.org", "西安邮电大学" => "http://www.xupt.edu.cn");
    return $rec;
}



