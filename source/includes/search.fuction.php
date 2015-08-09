<?php
/**
 * Created by PhpStorm.
 * User: lei
 * Date: 15-8-8
 * Time: 上午9:57
 * 模糊搜索跳转的的页面
 */

function get_parameter() {
    $key_word = $_GET['search']; //定义前端通过GET请求传过来的参数为search //
    return $key_word;
}

/*根据用户的关键词,返回问题的集合*/
/*返回值为所有匹配关键字的问题的标题的集合*/
function get_question_result() {
    $key_word = get_parameter();
    return search_question_title($key_word);

}
