<?php
/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 15-7-25
 * Time: 下午8:00
 */

    include '../config/oauth.config.php';
    include '../includes/oauth.class.php';
    $t = new oauth();

    echo '<pre>';
    if($_GET['state']){
        $token = $t->qq_callback();
        $openid = $t->qq_openid($token);
        $tmp = $t->get_user_info($token,$openid);
echo <<<EOT
<html>
	<head>
		<title>{$tmp['nickname']}</title>
	</head>
	<body>
	    <div>
		<fieldset>
			<legend>{$tmp['nickname']}</legend>
<img src="{$tmp['figureurl']}" /><br />
性别：{$tmp['gender']}<br />
城市：{$tmp['province']}·{$tmp['city']}<br />
出生年：{$tmp['year']}<br />
		</fieldset>
            <fieldset>
			<legend>DEBUG INFO</legend>
token：{$token}<br />
openid：{$openid}<br />
		</fieldset>
		</div>
	</body>
</html>
EOT;
        var_dump($tmp);
    }else{
        $t->qq_login();
    }
?>

