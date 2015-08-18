<?php

/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 2015/8/13
 * Time: 14:30
 */


class user
{
    private $ret;//状态
    private $userid;
    private $name;
    private $sex;
    private $imgs;
    private $db;

    function __construct(){
        if(isset($_SESSION['userid'])){
            $this->userid = $_SESSION['userid'];
            $this->name = $_SESSION['name'];
            $this->sex = $_SESSION['sex'];
            $this->imgs = $_SESSION['imgs'];
            $this->ret = 0;
        }
        else {
            $this->userid = 0;
            $this->ret = -1;
        }
        $this->db = new db_sql_functions();
    }

    //跳转QQ验证用户登陆信息，验证成功返回ture 否则返回false
    public function user_login_qq(){
        header("Location:/oauth/login.php");
        return true;
    }

    //跳转内部平台验证用户登陆信息，验证成功返回ture 否则返回false
    public function user_login_linux(){
        return false;
    }

    //退出当前用户，成功返回ture 否则返回false
    public function user_login_out(){
        //清除session
        //清除cookie
        session_unset();
        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(),'',time()-3600);
        }
        session_destroy();
        return true;
    }

    //获取当前登陆用户信息
    public function user_get_login(){
        //返回用户数据
        $tmp = array("ret"=>$this->ret,"userid"=>$this->userid,"name"=>$this->name,"sex"=>$this->sex,"imgs"=>$this->imgs);
        $tmp = json_encode($tmp);
        return $tmp;
    }

    //获取个人资料信息
    public function user_getinfo($userid){
        //通过数据库查询用户数据
        get_userinfo
        $result = $this->db->get_userinfo($userid);
        if ($result){
            $tmp = array("ret"=>"0");
            $t = array_combine($tmp,$result);
            return json_encode($t);
        }else{
            $tmp = array("ret"=>"-1");
            return json_encode($tmp);
        }
    }

    //获取用户权限，返回数据表中标识权限的字段所对应的值
    public function user_get_privilege($user_id){
        $result = $this->db->get_userinfo($user_id);
        if ($result){
            return $result['privilege'];
        }
        return false;
    }
}
