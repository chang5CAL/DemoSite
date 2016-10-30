<?php
require_once('config.php');
/*if(isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}*/

$error = "";
function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result['code'] = $ip_data->geoplugin_countryCode;
        $result['country'] = $ip_data->geoplugin_countryName;
        $result['city'] = $ip_data->geoplugin_city;
    }
    return $result;
}
/*$countryIP = getLocationInfoByIp();
$countrycode = $countryIP['code'];
$countryname = $countryIP['country'];*/

$countrycode = "IN";
$errors = array();

if(isset($_POST['signup']))
{
    function validStrLen($str, $min, $max){
        $len = strlen($str);
        if($len < $min){
            return "Username is too short, minimum is $min characters ($max max)";
        }
        elseif($len > $max){
            return "Username is too long, maximum is $max characters ($min min).";
        }
        elseif(!preg_match("/^[a-zA-Z0-9]+$/", $str))
        {
            return "Only use numbers and letters please";
        }
        else{
            //get the username
            $username = mysql_real_escape_string($_POST['username']);

            //mysql query to select field username if it's equal to the username that we check '
            $result = mysql_query('select username from userdata where username = "'. $username .'"');

            //if number of rows fields is bigger them 0 that means it's NOT available '
            if(mysql_num_rows($result)>0){
                //and we send 0 to the ajax request
                return "Error: Username not available";
            }
        }
        return TRUE;
    }

    $errors['username'] = validStrLen($_POST['username'], 4, 10);




    if($errors['username'] == 1)
    {

            $time = date('Y-m-d H:i:s', time());
            $query = "insert into userdata set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] . "', password='" . $_POST['password'] . "', joined = NOW(), country='$countrycode', last_active_timestamp = NOW(), about='test', sex = 'male', dob='1990', skype='dsf', facebook='dsfsdf', twitter='sdfsdf', googleplus='sdfsdf', instagram='none', picname='dsfsdf' ";
            var_dump($query);
            $query_result = mysql_query($query);
            var_dump(mysql_error());

            $user_id = mysql_insert_id();
            $username = $_POST['username'];
            echo $user_id . '\n';
            echo $username . '\n';
            var_dump($query_result);
            if (isset($user_id)) {
                $_SESSION['id'] = $user_id;
                $_SESSION['username'] = $username;
                //header("Location: index.php");
                //exit;
            } else {
                $error = "Error: Username & Password do not match";
            }

    }

}
if(isset($_POST['login']))
{
    $query = "SELECT id,username,password FROM userdata WHERE username='" . $_POST['username'] . "' AND password='" . $_POST['password'] . "' LIMIT 1";
    $query_result = mysql_query($query);
    $info = mysql_fetch_array($query_result);
    $user_id = $info['id'];
    $username = $info['username'];

    if(isset($user_id))
    {
        $_SESSION['id'] = $user_id;
        $_SESSION['username'] = $username;
        $query2 = mysql_query("update userdata set online = 1 where id = '".$_SESSION['id']."'");

        $res = mysql_query("UPDATE `userdata` SET online=1, last_active_timestamp = NOW() WHERE id = {$_SESSION['id']};");

        header("Location: index.php");
        exit;
    }
    else
    {
        $error = "Error: Username & Password do not match";
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="16x16">
    <meta name="keywords" content="php chat script, php ajax Chat,facebook similar chat, php mysql chat, chat script, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox, facebook similar inbox, facebook like chat" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <meta name="author" content="Zechat - Codentheme.com">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="16x16">

    <link rel="stylesheet" href="chatcss/loginstyle.css" type="text/css" />

    <script language="JavaScript">

        //////////F12 disable code////////////////////////
        document.onkeypress = function (event) {
            event = (event || window.event);
            if (event.keyCode == 123) {
                //alert('No F-12');
                return false;
            }
        }
        document.onmousedown = function (event) {
            event = (event || window.event);
            if (event.keyCode == 123) {
                //alert('No F-keys');
                return false;
            }
        }
        document.onkeydown = function (event) {
            event = (event || window.event);
            if (event.keyCode == 123) {
                //alert('No F-keys');
                return false;
            }
        }
        /////////////////////end///////////////////////


        //Disable right click script
        //visit http://www.rainbow.arch.scriptmania.com/scripts/
        var message="Don't try to be copy content";
        ///////////////////////////////////
        function clickIE() {if (document.all) {(message);return false;}}
        function clickNS(e) {if
        (document.layers||(document.getElementById&&!document.all)) {
            if (e.which==2||e.which==3) {(message);return false;}}}
        if (document.layers)
        {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
        else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
        document.oncontextmenu=new Function("return false")
        //
        function disableCtrlKeyCombination(e)
        {
//list all CTRL + key combinations you want to disable
            /*var forbiddenKeys = new Array('a', 'n', 'c', 'x', 'u', 's', 'v', 'j' , 'w','f12');
            var key;
            var isCtrl;
            if(window.event)
            {
                key = window.event.keyCode;     //IE
                if(window.event.ctrlKey)
                    isCtrl = true;
                else
                    isCtrl = false;
            }
            else
            {
                key = e.which;     //firefox
                if(e.ctrlKey)
                    isCtrl = true;
                else
                    isCtrl = false;
            }
//if ctrl is pressed check if other key is in forbidenKeys array
            if(isCtrl)
            {
                for(i=0; i<forbiddenKeys.length; i++)
                {
//case-insensitive comparation
                    if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
                    {
                        //alert('Key combination CTRL + '+String.fromCharCode(key) +' has been disabled.');
                        alert("Don't try to be copy content");
                        return false;
                    }
                }
            }*/
            return false;
        }
    </script>

</head>

<!-- <body onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" class="login"> -->
<!-- header starts here -->
<div id="facebook-Bar">
    <div id="facebook-Frame">
        <div id="logo">
            <img src="img/logo.png" alt="[logo:ZeChat]" />
        </div>
        <div id="header-main-right">
            <div id="header-main-right-nav">
                <form method="post" action="#" id="login_form" name="login_form">
                    <table border="0" style="border:none">

                        <tr>
                            <td >
                                <input type="text" tabindex="2" id="username" value="" name="username" class="inputtext radius1" >
                            </td>
                            <td ><input type="password" tabindex="2" id="pass" value="1234" name="password" class="inputtext radius1" ></td>
                            <td ><input type="submit" class="fbbutton" name="login" value="Login" /></td>
                        </tr>
                        <tr>
                            <td colspan="3"><label><span style="color:#ccc;">Login for testing
                                        <span style="color:#df6c6e;">
                                            <?php
                                            if(!empty($error)){
                                                echo "( ".$error." )";
                                            }
                                            ?>
                                        </span></span></label>
                            </td>

                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- header ends here -->

<div class="loginbox radius">
    <h2 style="color:#141823; text-align:center;">Welcome to Zechat</h2>
    <div class="loginboxinner radius">
        <div class="loginheader">
            <h4 class="title">Connect with friends and the world around you.</h4>

        </div><!--Featuresheader-->

        <div class="loginform">

            <form id="login" action="" method="post" enctype="multipart/form-data">
                <p>
                    <input type="text" id="name" name="name" placeholder="Full Name" value="" class="radius"  required/>
                </p>
                <p>
                    <input type="text" id="username" name="username" placeholder="Username" value="" class="radius" required/>
                </p>
                <p>
                    <input type="email" id="email" name="email" placeholder="Your Email" class="radius" required/>
                </p>
                <p>
                    <input type="password" id="password" name="password" placeholder="Set A Password" class="radius" required/>
                </p>
                <br>
                <p>
                    <button class="radius title" type="submit" name="signup">Sign Up for Zechat</button>
                </p>
            </form>
        </div><!--loginform-->
        <h4 class="title" style="color: red"><?php
            if(!empty($errors)){
                echo $errors['username']; // <--
            }
            ?></h4>


    </div><!--Featuresboxinner-->
</div><!--Featuresbox-->

<br>
<br>
</center>
</div>
<script>
    function validate() {
        if (document.myForm.name.value == "") {
            alert("Enter a name");
            document.myForm.name.focus();
            return false;
        }
        if (!/^[a-zA-Z]*$/g.test(document.myForm.name.value)) {
            alert("Invalid characters");
            document.myForm.name.focus();
            return false;
        }
    }

</script>


</body>

</html>