<?php

require_once 'db.class.php';

class db_sql_functions
{
    public __construct(){
        $dbconn = new \db_class();
        return $dbconn;
    }

    /*
	* 获取问题的标题
	* 参数：start_id(默认为0),limit_num（默认为20）
	* 返回值：问题title集合, question_id, user_id, answer_time
	*/
    public function get_question_list($start_id = 0, $limit_num = 20){
        $sql = "select title, qid, uid, gmt_create_time from app_faq_question limit $start_id, $limit_num"; 
        $re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
    }

	/*
	* 获取问题正文
	* 参数：question_id
	* 返回值：问题content
	*/
	public function get_question_content($question_id){
		$sql = "select content from app_faq_question where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}

   /*
    * 获取问题的标签
    * 参数：question_id
    * 返回值：(array) tags
    * 说明：标签之间使用（英文逗号）分隔
    */ 
    public function get_question_tags($question_id){
        $sql = "select tags from app_faq_question where qid='$question_id'";
        $re = $dbconn->query($sql);

        return explode(',', $re);
    }


	/*
	* 获取提问的用户
	* 参数：question_id
	* 返回值：user_id
	*/
	public function get_question_askuser($question_id){
		$sql = "select uid from app_faq_question where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 获取问题的状态
	* 参数：question_id
	* 返回值：(int) 正常：0(默认), 关闭：1, 删除：2, 置顶：4, 精华：8
	*/
	public function get_question_status($question_id){
		$sql = "select status from app_faq_question where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 添加问题
	* 参数：title, uid, content, tags(默认无标签)
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_question($title, $uid, $content, $tags=''){		
		$sql = "insert into app_faq_question(uid, title, content, tags) value($uid,'$title','$content','$tags')";
		$re = $dbconn->query($sql);
		
		return $re;
	}

	/*
	* 追加问题正文
	* 参数：question_id, add_content
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function append_question_comment($question_id, $add_content){
		$up_time = date('Y-m-d');
		$up_string = "'----------'.$up_time.'----------'";
		$sql = "select content from app_faq_question where qid=$question_id";
		$re = $dbconn->query($sql);
		if($re){
			$content = $re.$up_string.$add_content;
			$sql = "update app_faq_question set content='$content' where qid='$question_id'";
			$re = $dbconn->query($sql);
			if($re) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/*
	* 更新问题状态
	* 参数：question_id, (int)status
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function update_status($question_id, $status){
		$sql = "update app_faq_question set status=$status where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 删除问题
	* 参数：question_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_question($question_id){
		$sql = "delete from app_faq_question where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;	
	}
	
	/*
	* 模糊查找问题
	* 参数：keyword
	* 返回值：title_lists
	*/
	public function search_question_title($keyword){
		$sql = "select title from app_faq_question where binary ucase(title) like concat('%',ucase('$keyword'),'%')";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}


    /****************************/
	/*
	* 获取回复内容
	* 参数：question_id
	* 返回值：answer_lists
	*/
	public function get_answer_comment($question_id){
		$sql = "select content from app_faq_answer where qid='$question_id'";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}
	
	/*
	* 获取回复时间
	* 参数：answer_id
	* 返回值：unix时间戳
	*/
	public function get_answer_createtime($answer_id){
		$sql = "select gmt_create_time from app_faq_answer where aid='$answer_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 追加回复
	* 参数：answer_id, add_content
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function append_answer_comment($answer_id, $add_content){
		$up_time = date('Y-m-d');
		$up_string = "'----------'.$up_time.'----------'";
		$sql = "select content from app_faq_answer where aid=$answer_id";
		$re = $dbconn->query($sql);
		if($re){
			$content = $re.$up_string.$add_content;
			$sql = "update app_faq_answer set content='$content' where aid='$answer_id'";
			$re = $dbconn->query($sql);
			if($re) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	
   /*
	* 获取“赞”和“踩”人数
	* 参数：answer_id
	* 返回值：(array) 下标agree表示“赞”数目，disagree表示“踩”数目
	*/
	public function get_votenum($answer_id){
        $sql = "select vote from app_faq_answer where aid='$answer_id'";
        $re = $dbconn->query($sql);

        $arr = json_decode($re, true);
        $count_y = 0;
        $count_n = 0;
        
        foreach($arr as $key => $value){

            if($value == 1) $count_y ++;
            else $count_n ++;
        }

        return array('agree'=>$count_y,'disagree'=>$count_n);
    }

   /*
	* 添加“赞”或“踩”
	* 参数：answer_id, user_id, action(0踩,1赞)
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_answer_vote($answer_id, $user_id, $action){
		$result = $this->json_vote($answer_id, $user_id, $action, 1);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
   /*
	* 删除“赞”或“踩”
	* 参数：answer_id, user_id, action(0踩,1赞)
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_answer_vote($answer_id, $user_id, $action){
		$sql = "select vote from app_faq_answer where aid='$answer_id' and uid ='$uid'";
		$re = $dbconn->query($sql);
		$result = $this->json_vote($answer_id, $user_id, $action, 0);
		
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	* 添加回复
	* 参数：user_id, question_id, content
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_answer($user_id, $question_id, $content){
		$sql = "insert into app_faq_answer(uid, qid, content) values('$user_id', '$question_id', '$content')";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 删除回复
	* 参数：answer_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_answer($answer_id){
		$sql = "delete from app_faq_answer where aid='$answer_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 获取回复总数
	* 参数：question_id
	* 返回值：(int) answers
	*/
	public function get_answer_num($question_id){
		$sql = "select count(aid) from app_faq_answer where qid='$question_id'";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	
	
	
	
	/*
	* 添加关注
	* 参数：user_id, question_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function insert_follow($user_id, $question_id){
		$sql = "insert into app_faq_follow(uid, qid) values($user_id, $question_id)";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 删除关注
	* 参数：user_id, question_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_follow($user_id, $question_id){
		$sql = "deletc from app_faq_follow where uid=$user_id and qid=$question_id";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 获取关注的问题id
	* 参数：user_id
	* 返回值：question_lists
	*/
	public function get_follow_question($user_id){
		$sql =  "select qid from app_faq_follow where uid=$user_id";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}
	
	/*
	* 获取关注某问题的全部关注用户id
	* 参数：question_id	
	* 返回值：user_lists
	*/
	public function get_follow_id($question_id){
		$sql =  "select uid from app_faq_follow where qid=$question_id";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}
	
	
	
	
    /************************************/
	/*
	* 添加通知
	* 参数：user_id, content, link
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_notify($user_id, $content, $link){
		$sql =  "insert into app_faq_notify(uid, content, link) values($user_id, '$content', '$link') ";
		$re = $dbconn->query($sql);
		
		return $re;
	}

	/*
	* 获取通知
	* 参数：user_id
	* 返回值：notify_lists
	*/
	public function get_notify($user_id){
		$sql =  "select nid from app_faq_notify where uid=$user_id";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}
	
	/*
	* 标记已读
	* 参数：notify_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function change_notify_status($notify_id){
		$sql =  "update app_faq_notify set nid=$notify_id";
		$re = $dbconn->query($sql);
		
		return $re;
	}
	
	/*
	* 通知符合条件的人
	* 参数：question_id
	* 返回值：userid_lists
	*/
	public function notice_all_user(){
		$sql =  "select uid from app_faq_follow where qid=$question_id";
		$re = $dbconn->query($sql);
		$result = array();
        while($res = mysql_fetch_array($re)){
			array_push($result, $res);
		}
		
        return $result;
	}
	
	
	/*
	* json 操作
	* 参数：answer_id, user_id, action, flag
    * 返回值：(bool) 成功：true, 失败：false
    * 说明：flag为1,表示add，反之，为del
	*/
	public function json_vote($answer_id, $user_id, $action, $flag){
		$sql = "select vote from app_faq_answer where aid=$answer_id and uid =$uid";
        $re = $dbconn->query($sql);

        $arr = json_decode($re, true);
        foreach($arr as $key => $value){

            if($key == $user_id){

                if($flag){  //找到key && flag == 1,即添加失败

                    return false;
                }else{  //找到key && flag == 0,进行删除操作
                    
                    unset($arr[$key]);
                    $json = json_encode($arr);
                    $sql = "update app_faq_answer set vote='$json' where aid=$answer_id and uid=$uid";
                    $dbconn->query($sql);

                    return true;
                }

            }
        }
        if($flag){  //未找到key && flag == 1,则添加
                
            $add_vote = array($user_id => '1');
            array_push($arr, $add_vote);
            $json = json_encode($arr);
            $sql = "update app_faq_answer set vote='$json' where aid=$answer_id and uid=$uid";
            $dbconn->query($sql);
                
            return true;
        }else{  //未找到key && flag == 0,即删除失败

            return false;
        }

?>
