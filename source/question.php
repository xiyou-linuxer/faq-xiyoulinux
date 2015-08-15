<?php

	/*
	 * 问题详情页 
	 * 相关说明：$detial(title、content、tags、uid、ctime)
	 * 			$answers(aid, uid, content, vote, ctime)
	 */

require_once 'includes/db_function.class.php';
require_once 'includes/errshow.class.php';

$qid = $_GET['q'];

$db = new db_sql_functions();
$err = new Errshow();
$detial = $db->get_question_detial($qid);
if($detial['status'] == 1){
    $re = $err->has_down();
    return;

}elseif($detial['status'] == 0){
    $re = $err->has_del();
    return;

}else{
    $smarty->assign('question', $detial);
    $answers = $db->get_answer_lists($qid);
    $smarty->assign('answers', $answers);
    
    $smarty->display('question.tpl');
}

?>
