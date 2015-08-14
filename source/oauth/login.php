<?php
/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 15-7-25
 * Time: 下午8:00
 */

    require_once '../config/oauth.config.php';
    require_once '../includes/oauth.class.php';
    require_once "../includes/db_function.class.php";

    $t = new oauth();

    echo '<pre>';

    //先判断是不是需要登录
    //如果用户不需要登录，就跳回主页
    if (isset($_SESSION['userid']) && ($_SESSION['userid'] != 0)){
        //跳回主页
        header("Location:/");
        exit;
    }

    //QQ用户登录信息页面，用于QQ登录
    if($_GET['state']){
        //登录回调，获取信息
        $token = $t->qq_callback();
        $openid = $t->qq_openid($token);
        $tmp = $t->get_user_info($token,$openid);
        switch($tmp['gender']){
            case "男":
                $sex = 1;
                break;
            case "女":
                $sex = 0;
                break;
            default:
                $sex = -1;
        }
        $db = new db_sql_functions();
        $userid = $db->update_userinfo($openid,$tmp['nickname'],'$sex',$tmp['figureurl_qq_1']);
        if ($userid < 0){
            //失败
            header("Location:/");
            exit;
        }
        //登录成功，写入session
        $_SESSION['userid'] = $userid;
        $_SESSION['name'] = $tmp['nickname'];
        $_SESSION['sex'] = $sex;
        $_SESSION['imgs'] = $tmp['figureurl_qq_1'];
        header("Location:/");
        exit;
    }else{
        //未登录或者已经登录
        $t->qq_login();
    }
?>

