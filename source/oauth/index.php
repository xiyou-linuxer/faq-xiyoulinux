<?php
/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 15-8-2
 * Time: 下午2:11
 */
session_start();
if (isset($_SESSION['userid']) || $_SESSION['userid'] == 0){
    //用户没有登陆
    echo "<a href='login.php'><img src='../imgs/qqoauth_230.png' alt='使用QQ登陆'></a>";
    $flag = 0;
}else{
    //登陆了
    echo $_SESSION['username']." welcome.<img src='".$_SESSION['imgs']."'>";
}