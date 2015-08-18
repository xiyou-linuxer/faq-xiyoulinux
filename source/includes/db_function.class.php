<?php

class db_sql_functions
{
    private $dbconn;

    function __construct()
    {
        $this->dbconn = new \db();
        return $this;
    }

    /*
    * 获取问题的标题
    * 参数：start_id(默认为0),limit_num（默认为20）
    * 返回值：问题title集合, question_id, user_id, time
    */
    function get_question_list($start_id = 0, $limit_num = 20)
    {
        $sql = "select title, qid, uid, gmt_create_time as time from app_faq_question limit $start_id, $limit_num";
        $result = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row);
        }

        if ($rows)
            return $rows;
        else
            return false;
    }

    /*
    * 获取问题详情
    * 参数：question_id
    * 返回值：(array) title、content、tags、uid、ctime
    */
    public function get_question_detial($question_id)
    {
        $sql = "select title, content, tags, uid, gmt_create_time as ctime from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();

        if ($result)
            return $result;
        else
            return false;
    }

    /*
    * 获取问题正文
    * 参数：question_id
    * 返回值：问题的：content
    */
    public function get_question_content($question_id)
    {
        $sql = "select content from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();

        if ($result)
            return $result['content'];
        else
            return false;
    }

    /*
    * 获取问题的标签
    * 参数：question_id
    * 返回值：(array) tags
    * 说明：标签之间使用（英文逗号）分隔
    */
    public function get_question_tags($question_id)
    {
        $sql = "select tags from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();

        if ($result)
            return explode(',', $result['tags']);
        else
            return false;
    }


    /*
    * 获取提问的用户
    * 参数：question_id
    * 返回值：user_id
    */
    public function get_question_askuser($question_id)
    {
        $sql = "select uid from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();

        if ($result)
            return $result['uid'];
        else
            return false;
    }

    /*
    * 获取问题的状态
    * 参数：question_id
    * 返回值：(int) 正常：0(默认), 关闭：1, 删除：2, 置顶：4, 精华：8
    */
    public function get_question_status($question_id)
    {
        $sql = "select status from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();

        if ($result)
            return $result['status'];
        else
            return false;
    }

    /*
    * 添加问题
    * 参数：title, uid, content, tags(默认无标签)
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function add_question($title, $uid, $content, $tags = '')
    {
        $time = date('Y-m-d H:i:s');
        $sql = "insert into app_faq_question(uid, title, content, tags, gmt_update_time) value($uid,'$title','$content','$tags', '$time')";
        $re = $this->dbconn->query($sql);

        return $re;
    }

    /*
    * 追加问题正文
    * 参数：question_id, add_content
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function append_question_conent($question_id, $add_content)
    {
        $up_time = date('Y-m-d');
        $up_string = "----------" . $up_time . "----------";
        $sql = "select content from app_faq_question where qid=$question_id";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();
        $con = $result['content'];

        if ($con) {
            $content = $con . $up_string . $add_content;
            $sql = "update app_faq_question set content='$content' where qid='$question_id'";
            $re = $this->dbconn->query($sql);
            return $re;
        } else {
            return false;
        }
    }

    /*
    * 更新问题状态
    * 参数：question_id, (int)status
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function update_status($question_id, $status)
    {
        $sql = "update app_faq_question set status=$status where qid='$question_id'";
        $re = $this->dbconn->query($sql);

        return $re;
    }

    /*
    * 删除问题
    * 参数：question_id
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function delete_question($question_id)
    {
        $sql = "delete from app_faq_question where qid='$question_id'";
        $re = $this->dbconn->query($sql);

        return $re;
    }

    /*
    * 模糊查找问题
    * 参数：keyword
    * 返回值：title_lists
    */
    public function search_question_title($keyword)
    {
        $sql = "select title from app_faq_question where binary ucase(title) like concat('%',ucase('$keyword'),'%')";
        $re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {
            array_push($rows, $row);
        }

        if ($rows)
            return $rows;
        else
            return false;
    }


    /****************************/
    /*
    * 获取回复内容列表
    * 参数：question_id
    * 返回值：(answer_lists) content, uid, (array) vote, time
    */
    public function get_answer_lists($question_id)
    {
        $sql = "select aid, content, uid, vote, gmt_create_time as ctime from app_faq_answer where qid='$question_id'";
        $re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {

            $row['vote'] = $this->get_votenum($row['aid']);
            array_push($rows, $row);
        }

        if ($rows)
            return $rows;
        else
            return false;
    }

    /*
    * 追加回复
    * 参数：answer_id, add_content
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function append_answer_comment($answer_id, $add_content)
    {
        $up_time = date('Y-m-d');
        $up_string = "----------" . $up_time . "----------";
        $sql = "select content from app_faq_answer where aid=$answer_id";
        $re = $this->dbconn->query($sql);
        $result = $re->fetch_assoc();
        $con = $result['content'];
        if ($con) {
            $content = $con . $up_string . $add_content;
            $sql = "update app_faq_answer set content='$content' where aid='$answer_id'";
            $re = $this->dbconn->query($sql);
            return $re;
        } else {
            return false;
        }

    }

    /*
    * 获取“赞”和“踩”人数
    * 参数：answer_id
    * 返回值：(array) 下标vote_a表示“赞”数目，vote_d表示“踩”数目
    */
    public function get_votenum($answer_id)
    {
        $sql = "select vote from app_faq_answer where aid=$answer_id";
        $re = $this->dbconn->query($sql);
        $row = $re->fetch_assoc();
        $json = $row['vote'];

        $count_a = 0;
        $count_d = 0;

        $arr = json_decode($json, true);
        for ($i = 0; $i < count($arr); $i++) {
            foreach ($arr[$i] as $key => $value) {
                if ($value == 1) $count_a ++;
                else $count_d ++;
            }
        }
        return array('vote_a' => $count_a, 'vote_d' => $count_d);
    }

    /*
    * 添加“赞”或“踩”
    * 参数：answer_id, user_id, self_id, action(0踩,1赞)
    * 返回值：(bool) 成功：true, 失败：false
    */
    public function add_answer_vote($answer_id, $user_id, $self_id, $action)
    {
        $sql = "select vote from app_faq_answer where aid=$answer_id and uid =$user_id";
        $re = $this->dbconn->query($sql);
        $row = $re->fetch_assoc();
        $json = $row['vote'];

        if($json != 'no') { //vote为空时
            $arr = json_decode($json, true);
            for ($i = 0; $i < count($arr); $i++) {
                foreach ($arr[$i] as $key => $value) {
                    if ($key == $self_id)
                        return false;
                }
            }
        }
        $add_vote = array($self_id => $action);
        array_push($arr, $add_vote);
        $json = json_encode($arr);
        $sql = "update app_faq_answer set vote='$json' where aid=$answer_id and uid=$user_id";
        $this->dbconn->query($sql);

        return true;
    }

	/*
	* 删除“赞”或“踩”
	* 参数：answer_id, user_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_answer_vote($answer_id, $user_id, $self_id)
	{
        $sql = "select vote from app_faq_answer where aid=$answer_id and uid =$user_id";
        $re = $this->dbconn->query($sql);
        $row = $re->fetch_assoc();
        $json = $row['vote'];

        if($json == 'no') {  //vote为空时
            return false;
        }else{
            $arr = json_decode($json, true);
            for ($i = 0; $i < count($arr); $i++) {
                foreach ($arr[$i] as $key => $value) {
                    if ($key == $self_id) { //delete
                        unset($arr[$i]);
                        $json = json_encode($arr);
                        $sql = "update app_faq_answer set vote='$json' where aid=$answer_id and uid=$user_id";
                        $this->dbconn->query($sql);

                        return true;
                    }
                }
            }
            return false;
        }
	}

	/*
	* 添加回复
	* 参数：user_id, question_id, content
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_answer($user_id, $question_id, $content)
	{
		$sql = "insert into app_faq_answer(uid, qid, content, vote) values('$user_id', '$question_id', '$content', NULL)";
		$re = $this->dbconn->query($sql);

		return $re;
	}

	/*
	* 删除回复
	* 参数：answer_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_answer($answer_id)
	{
		$sql = "delete from app_faq_answer where aid='$answer_id'";
		$re = $this->dbconn->query($sql);

		return $re;
	}

	/*
	* 获取回复总数
	* 参数：question_id
	* 返回值：(int) answers
	*/
	public function get_answer_num($question_id)
	{
		$sql = "select count(aid) as num from app_faq_answer where qid='$question_id'";
		$re = $this->dbconn->query($sql);
        $row = $re->fetch_assoc();
        $rows = $row['num'];

        if ($rows)
            return $rows;
        else
            return false;
	}

	/*
	* 添加关注
	* 参数：user_id, question_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function insert_follow($user_id, $question_id)
	{
		$sql = "insert into app_faq_follow(uid, qid) values($user_id, $question_id)";
		$re = $this->dbconn->query($sql);

		return $re;
	}

	/*
	* 删除关注
	* 参数：user_id, question_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function delete_follow($user_id, $question_id)
	{
		$sql = "delete from app_faq_follow where uid=$user_id and qid=$question_id";
		$re = $this->dbconn->query($sql);

		return $re;
	}

	/*
	* 获取关注的问题id
	* 参数：user_id
	* 返回值：question_lists (array)
	*/
	public function get_follow_question($user_id)
	{
		$sql = "select qid from app_faq_follow where uid=$user_id";
		$re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {
            array_push($rows, $row['qid']);
        }

        if ($rows)
            return $rows;
        else
            return false;
	}

	/*
	* 获取关注某问题的全部关注用户id
	* 参数：question_id	
	* 返回值：user_lists
	*/
	public function get_follow_id($question_id)
	{
		$sql = "select uid from app_faq_follow where qid=$question_id";
		$re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {
            array_push($rows, $row['uid']);
        }

        if ($rows)
            return $rows;
        else
            return false;
	}


	/*
	* 添加通知
	* 参数：user_id, content, link
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function add_notify($user_id, $content, $link)
	{
		$sql = "insert into app_faq_notify(uid, content, link) values($user_id, '$content', '$link') ";
		$re = $this->dbconn->query($sql);

		return $re;
	}

	/*
	* 获取通知
	* 参数：user_id
	* 返回值：notify_lists
	*/
	public function get_notify($user_id)
	{
		$sql = "select nid from app_faq_notify where uid=$user_id";
		$re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {
            array_push($rows, $row['nid']);
        }

        if ($rows)
            return $rows;
        else
            return false;
	}

	/*
	* 标记已读
	* 参数：notify_id
	* 返回值：(bool) 成功：true, 失败：false
	*/
	public function change_notify_status($notify_id)
	{
		$sql = "update app_faq_notify set status=1 where nid=$notify_id";
		$re = $this->dbconn->query($sql);

		return $re;
	}


	/*
	* 通知符合条件的人
	* 参数：question_id
	* 返回值：userid_lists
	*/
	public function notice_all_user($question_id)
	{
		$sql = "select uid from app_faq_follow where qid=$question_id";
		$re = $this->dbconn->query($sql);
        $rows = array();
        while ($row = $re->fetch_assoc()) {
            array_push($rows, $row['uid']);
        }

        if ($rows)
            return $rows;
        else
            return false;
	}

	/**
	 * 响应QQ登录事件，更新数据
	 * 参数：openid, name, sex, imgs
	 * 返回值：(bool) 成功：userid, 失败：失败原因id{-1，存在用户但数据库更新失败；-2，不存在用户，插入新用户信息失败，-3，其他}
	 * 说明：sex 男1女2
	 */
	public function update_userinfo($openid,$name,$sex,$imgs){
		//判断参数合法性，需判断参数
		//判断openid是不是已经在数据库中
		$sql = "SELECT userid FROM app_faq_user WHERE openid = ".$openid;
		$result = $this->dbconn->query($sql);
		if($result){
			//数据库查询结果唯一，则存在该用户，更新用户信息
			$rows=$result->fetch_assoc();
			$userid = $rows['userid'];
			$sql = "UPDATE `app_faq_user` SET `name`='{$name}', `sex`='{$sex}', `imgs`='{$imgs}' WHERE `userid` = '{$userid}'";
			$result = $this->dbconn->query($sql);
			if ($result)
				return $userid;
			else
				return -1;
		}else {
			//不存在该用户，插入新用户信息
			$sql = "INSERT INTO `app_faq_user` (`userid`, `openid`, `name`, `sex`, `imgs`, `privilege`) values(NULL,'{$openid}','{$name}','{$sex}','{$imgs}','0')";
			$result = $this->dbconn->query($sql);
			if ($result) {
				//新用户插入成功，查询userid，并返回
				$sql = "SELECT userid FROM app_faq_user WHERE openid = " . $openid;
				$result = $this->dbconn->query($sql);
				if ($result && $result->num_rows > 0) {
					$rows = $result->fetch_assoc();
					$userid = $rows['userid'];
					return $userid;
				} else {
					return -3;
				}
			} else {
				//新用户插入失败
				return -2;
			}
		}
	}

	/**
	 * 获取用户信息
	 * 参数：user_id
	 * 返回值：用户信息数组
	 * 说明：无
	 */
	public function get_userinfo($user_id){
		//id合法性判断
		if ($user_id <1000 || $user_id > 100000)
			return false;
		$sql = "SELECT * FROM app_faq_user WHERE userid = ".$user_id;
		$result = $this->dbconn->query($sql);
		if($result && $result->num_rows>0){
			$rows = $result->fetch_assoc();
			return $rows;
		}else{
			return false;
		}
	}

	/**
	 * 内部用户关联QQ
	 * 参数:user_id,openid
	 * 返回值:bool
	 * 说明：关联内部用户QQ信息
	 */
	public function connect_qq_linux($user_id,$openid){
		//合法性判断
		//插入关联信息
		$sql = "SELECT userid FROM app_faq_user WHERE openid = ".$openid;
		$result = $this->dbconn->query($sql);
		if($result){
			return false;
		}else {

			$sql = "SELECT * FROM app_faq_user WHERE userid = ".$user_id;
			$result = $this->dbconn->query($sql);
			if ($result){
				return false;
			}
			//关联
			$sql = "INSERT INTO `app_faq_user` (`userid`, `openid`, `name`, `sex`, `imgs`, `privilege`) values('{$user_id}','{$openid}','linuxer','0','http://xiyoulinux.qiniudn.com/linuxer.png','0')";
			$result = $this->dbconn->query($sql);
			if ($result)
				return $user_id;
			else
				return -1;
		}
	}
}
