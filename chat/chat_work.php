<?php
require_once('config.php');
/*
Copyright (c) 2015 Devendra Katariya (bylancer.com)
*/

if ($_GET['action'] == "get_all_msg") { get_all_msg(); } 
if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if ($_GET['action'] == "sendchat") { sendChat(); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession(); } 
if ($_GET['action'] == "typingstatus") { typingStatus(); }


if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

if (!isset($_SESSION['chatpage'])) {
	$_SESSION['chatpage'] = 1;	
}

/*This Function is for Emotions by php - But not used its was testing*/
/*function emoticons($text) {
    $icons = array(
        'o:)'    =>  '<img src="emotions-fb/angel.gif"/>',
        ':3'   =>  '<img src="emotions-fb/colonthree.gif" />',
        'o.O'   =>  '<img src="emotions-fb/confused.gif"/>',
        ':('    =>  '<img src="emotions-fb/cry.gif"/>',
        '3:)'   =>  '<img src="emotions-fb/devil.gif" />',
        ':O'   =>  '<img src="emotions-fb/gasp.gif"/>',
        ':('   =>  '<img src="emotions-fb/frown.gif"/>',
        '8)'   =>  '<img src="emotions-fb/glasses.gif"/>',
        ':D'   =>  '<img src="emotions-fb/grin.gif"/>',
        '>:('   =>  '<img src="emotions-fb/grumpy.gif"/>',
        '<3'   =>  '<img src="emotions-fb/heart.gif"/>',
        '^_^'   =>  '<img src="emotions-fb/kiki.gif"/>',
        ':*'   =>  '<img src="emotions-fb/kiss.gif"/>',
        ':v'   =>  '<img src="emotions-fb/pacman.gif"/>',
        ':)'   =>  '<img src="emotions-fb/smile.gif"/>',
        '-_-'   =>  '<img src="emotions-fb/squint.gif"/>',
        '8|'   =>  '<img src="emotions-fb/sunglasses.gif"/>',
        ':p'   =>  '<img src="emotions-fb/tongue.gif"/>',
        ':/'   =>  '<img src="emotions-fb/unsure.gif"/>',
        '>:O'   =>  '<img src="emotions-fb/upset.gif"/>',
        ';)'   =>  '<img src="emotions-fb/wink.gif"/>',

    );
    $text = " ".$text." ";
    foreach ($icons as $search => $replace){
        $text = str_replace(" ".$search." ", " ".$replace." ", $text);
    }
    return $text;
}*/
/* END This Function is for Emotions by php - But not used its was testing*/

function get_all_msg() {

    $perPage = 10;

	$sql = "select * from messages where ((to_uname = '".mysql_real_escape_string($_SESSION['username'])."' AND from_uname = '".mysql_real_escape_string($_GET['client'])."' ) OR (to_uname = '".mysql_real_escape_string($_GET['client'])."' AND from_uname = '".mysql_real_escape_string($_SESSION['username'])."' )) order by message_id DESC ";
	
	$page = 1;
	if(!empty($_GET["page"])) {
	$_SESSION['chatpage'] = $page = $_GET["page"];
	}
	
	$start = ($page-1)*$perPage;
	if($start < 0) $start = 0;
	
	$query =  $sql . " limit " . $start . "," . $perPage; 

	$query = mysql_query($query);
	
	if(empty($_GET["rowcount"])) {
	$_GET["rowcount"] = $rowcount = mysql_num_rows(mysql_query($sql));
	}

	$pages  = ceil($_GET["rowcount"]/$perPage);
	
	$chatBoxes = array();
	$items = '';
	if(!empty($query)) {

	}

	while ($chat = mysql_fetch_array($query)) {
		
		$picname = "";
		$picname2 = "";

        $query1 = "SELECT picname,online FROM userdata WHERE username='" .mysql_real_escape_string($chat['from_uname']). "' LIMIT 1";
        $query_result = mysql_query ($query1) OR error(mysql_error());
        while ($info = mysql_fetch_array($query_result))
        {
            $picname = "small".$info['picname'];
            $status = $info['online'];
        }

        $query4 = "SELECT picname,online FROM userdata WHERE username='" .mysql_real_escape_string($chat['to_uname']). "' LIMIT 1";
        $query_result4 = mysql_query ($query4) OR error(mysql_error());
        while ($info4 = mysql_fetch_array($query_result4))
        {
            $picname2 = "small".$info4['picname'];
        }

		
		if($picname == "small")
			$picname = "avatar_default.png";
		
		if($picname2 == "small")
			$picname2 = "avatar_default.png";

		if($status == "0")
			$status = "Offline";
		else
			$status = "Online";
		
		
		if (!isset($_SESSION['openChatBoxes'][$_GET['client']]) && isset($_SESSION['chatHistory'][$_GET['client']])) {
			$items = $_SESSION['chatHistory'][$_GET['client']];
		}

		$chat['message_content'] = sanitize($chat['message_content']);
		
		if($chat['from_uname'] == $_SESSION['username'])
		{
			$u = 1;	
			$sespic = $picname;
		}
		else{
			$u = 2;	
			$sespic = $picname2;
		}

        if (strpos($chat['message_content'], sanitize('file_name')) !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

		$items .= <<<EOD
					   {
			"s": "0",
			"sender": "{$chat['from_uname']}",
			"f": "{$_GET['client']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"st": "{$status}",
			"page": "{$_SESSION['chatpage']}",
			"pages": "{$pages}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chat['message_content']}"
	   },
EOD;

	if (!isset($_SESSION['chatHistory'][$_GET['client']])) {
		$_SESSION['chatHistory'][$_GET['client']] = '';
	}

	$_SESSION['chatHistory'][$_GET['client']] .= <<<EOD
	{
			"s": "0",
			"sender": "{$chat['from_uname']}",
			"f": "{$_GET['client']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"spic": "{$sespic}",
			"st": "{$status}",
			"page": "{$_SESSION['chatpage']}",
			"pages": "{$pages}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chat['message_content']}"
	   },
EOD;
		
		unset($_SESSION['tsChatBoxes'][$_GET['client']]);
		$_SESSION['openChatBoxes'][$_GET['client']] = $chat['message_date'];
	}

	if (!empty($_SESSION['openChatBoxes'])) 
	{
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) 
		{
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "$time";
			if ($now > 180) 
			{
				$items .= <<<EOD
{
"s": "2",
"sender": "{$chat['from_uname']}",
"f": "$chatbox",
"x": "{$chat['from_id']}",
"p": "{$picname}",
"p2": "{$picname2}",
"spic": "{$sespic}",
"st": "{$status}",
"page": "{$_SESSION['chatpage']}",
"pages": "{$pages}",
"m": "{$message}"
},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"sender": "{$chat['from_uname']}",
"f": "$chatbox",
"x": "{$chat['from_id']}",
"p": "{$picname}",
"p2": "{$picname2}",
"spic": "{$sespic}",
"st": "{$status}",
"page": "{$_SESSION['chatpage']}",
"pages": "{$pages}",
"m": "{$message}"
},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}
    $sql = "update messages set recd = 1 where to_uname = '".mysql_real_escape_string($_SESSION['username'])."' and recd = 0";
    $query = mysql_query($sql);

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
	
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatHeartbeat() {
	//echo $_SESSION['username'];
	
	$sql = "select * from messages where (to_uname = '".mysql_real_escape_string($_SESSION['username'])."' AND recd = 0) order by message_id ASC";
	$query = mysql_query($sql);
	$items = '';

	$chatBoxes = array();

	while ($chat = mysql_fetch_array($query)) {
		//echo "in loop";
		$picname = "";
		$picname2 = "";

        $query1 = "SELECT picname,online FROM userdata WHERE username='" .mysql_real_escape_string($chat['from_uname']). "' LIMIT 1";
        $query_result = mysql_query ($query1) OR error(mysql_error());
        while ($info = mysql_fetch_array($query_result))
        {
            $picname = "small".$info['picname'];
            $status = $info['online'];
        }

        $query4 = "SELECT picname FROM userdata WHERE username='" .mysql_real_escape_string($_SESSION['username']). "' LIMIT 1";
        $query_result4 = mysql_query ($query4) OR error(mysql_error());
        while ($info4 = mysql_fetch_array($query_result4))
        {
            $picname2 = "small".$info4['picname'];
        }

		if($picname == "small")
			$picname = "avatar_default.png";
		
		if($picname2 == "small")
			$picname2 = "avatar_default.png";

		if($status == "0")
			$status = "Offline";
		else
			$status = "Online";
		
		
		if (!isset($_SESSION['openChatBoxes'][$chat['from_uname']]) && isset($_SESSION['chatHistory'][$chat['from_uname']])) {
			$items = $_SESSION['chatHistory'][$chat['from_uname']];
		}

		$chat['message_content'] = sanitize($chat['message_content']);
		
		if($chat['from_uname'] == $_SESSION['username'])
		{
			$u = 1;	
			$sespic = $picname;
		}
		else{
			$u = 2;	
			$sespic = $picname2;
		}

        if (strpos($chat['message_content'], sanitize('file_name')) !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from_uname']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"spic": "{$sespic}",
			"st": "{$status}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chat['message_content']}"
	   },
EOD;

	if (!isset($_SESSION['chatHistory'][$chat['from_uname']])) {
		$_SESSION['chatHistory'][$chat['from_uname']] = '';
	}

	$_SESSION['chatHistory'][$chat['from_uname']] .= <<<EOD
	{
			"s": "0",
			"f": "{$chat['from_uname']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"spic": "{$sespic}",
			"st": "{$status}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chat['message_content']}"
	   },
EOD;
		
		unset($_SESSION['tsChatBoxes'][$chat['from_uname']]);
		$_SESSION['openChatBoxes'][$chat['from_uname']] = $chat['message_date'];
	}

	if (!empty($_SESSION['openChatBoxes'])) 
	{
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) 
		{
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "$time";
			if ($now > 180) 
			{
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"x": "{$chat['from_id']}",
"p": "{$picname}",
"p2": "{$picname2}",
"spic": "{$sespic}",
"st": "{$status}",
"m": "{$message}"
},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"x": "{$chat['from_id']}",
"p": "{$picname}",
"p2": "{$picname2}",
"spic": "{$sespic}",
"st": "{$status}",
"m": "{$message}"
},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}

	$sql = "update messages set recd = 1 where to_uname = '".mysql_real_escape_string($_SESSION['username'])."' and recd = 0";
	$query = mysql_query($sql);

    $res = mysql_query("UPDATE `userdata` SET online=1, last_active_timestamp = NOW() WHERE id = {$_SESSION['id']};");

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo $_SESSION['username'];?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	$from = $_SESSION['username'];
	$to = $_POST['to'];
	$to_id = $_POST['toid'];
	$from_id = $_SESSION['id'];
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}
	
	$picname = "";
	$picname2 = "";

    $query1 = "SELECT picname,online FROM userdata WHERE username='" .mysql_real_escape_string($to). "' LIMIT 1";
    $query_result = mysql_query ($query1) OR error(mysql_error());
    while ($info = mysql_fetch_array($query_result))
    {
        $picname = "small".$info['picname'];
        $status = $info['online'];
    }

    $query4 = "SELECT picname FROM userdata WHERE username='" .mysql_real_escape_string($_SESSION['username']). "' LIMIT 1";
    $query_result4 = mysql_query ($query4) OR error(mysql_error());
    while ($info4 = mysql_fetch_array($query_result4))
    {
        $picname2 = "small".$info4['picname'];
    }
	
	if($picname == "small")
		$picname = "avatar_default.png";
	if($picname2 == "small")
		$picname2 = "avatar_default.png";
	
	if($status == "0")
		$status = "Offline";
	else
		$status = "Online";

	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"x": "{$to_id}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"st": "{$status}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);

	$sql = "insert into messages (from_uname,to_uname,from_id,to_id,message_content,message_date,recd) values ('".mysql_real_escape_string($from)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($from_id)."','".mysql_real_escape_string($to_id)."','".mysql_real_escape_string($message)."',NOW(), 0)";

	$query = mysql_query($sql);

	echo "RANDOM!!!!!";
	var_dump(mysql_error());
	var_dump($query);
	echo "after random";
    $res = mysql_query("UPDATE `userdata` SET online=1, last_active_timestamp = NOW() WHERE id = {$_SESSION['id']};");
    echo "1";
	exit(0);
}

function typingStatus() {
	$from = $_SESSION['username'];
	$to = $_POST['to'];
	$to_id = $_POST['toid'];
	$from_id = $_SESSION['id'];
	$typing = $_POST['typing'];

	
	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}

	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"ty": "{$typing}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);

	echo "1";
	exit(0);
}


function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}



?>