<?php

class Errshow
{
	
	public __construct(){
	
	}
	
	// 问题已关闭时的错误提示
	public function has_down(){
		$msg = '该问题已被管理员关闭！';
		$smarty->assgin('error', $msg);
		$smarty->display('err.tpl');
		
		
	}
	
	// 问题已删除时的错误提示
	public function has_del(){
		$msg = '该问题已被管理员删除！';
		$smarty->assgin('error', $msg);
		$smarty->display('err.tpl');
	}

}

?>
