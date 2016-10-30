<?php
require_once('config.php');

if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$query1 = "SELECT * FROM userdata where id = '".$_SESSION['id']."'";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_assoc($result1);
$string = $row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "")
    $sesuserpic = "avatar_default.png";


$error = "";

if(isset($_POST['Submit']))
{
    if($_FILES['file']['name'] != "")
    {
        $uploaddir = 'storage/user_image/';
        $original_filename = $_FILES['file']['name'];

        $extensions = explode(".", $original_filename);
        $extension = $extensions[count($extensions) - 1];
        $uniqueName =  $string . "." . $extension;
        $uploadfile = $uploaddir . $uniqueName;

        $file_type = "file";

        if ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") {
            $file_type = "image";

            $size = filesize($_FILES['file']['tmp_name']);

            $image = $_FILES["file"]["name"];
            $uploadedfile = $_FILES['file']['tmp_name'];

            if ($image) {
                if ($extension == "jpg" || $extension == "jpeg") {
                    $uploadedfile = $_FILES['file']['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($extension == "png") {
                    $uploadedfile = $_FILES['file']['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                } else {
                    $src = imagecreatefromgif($uploadedfile);
                }

                list($width, $height) = getimagesize($uploadedfile);

                $newwidth = 225;
                $newheight = ($height / $width) * $newwidth;
                $tmp = imagecreatetruecolor($newwidth, $newheight);

                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                $filename = $uploaddir . "small" . $uniqueName;

                imagejpeg($tmp, $filename, 100);

                imagedestroy($src);
                imagedestroy($tmp);
            }


        }
        //else if it's not bigger then 0, then it's available '
        //and we send 1 to the ajax request
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            //$time = date('Y-m-d H:i:s', time());
            $query = "Update userdata set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', about='" . $_POST['about'] . "', sex='" . $_POST['sex'] . "', dob='" . $_POST['dob'] . "', skype='" . $_POST['skype'] . "', facebook='" . $_POST['facebook'] . "', twitter='" . $_POST['twitter'] . "', googleplus='" . $_POST['googleplus'] . "', instagram='" . $_POST['instagram'] . "', picname='$uniqueName' WHERE id = {$_SESSION['id']} ";
            $query_result = mysql_query($query);

            header("Location: index.php");
            exit;
        }
    }
    else{
        //$time = date('Y-m-d H:i:s', time());
        $query = "Update userdata set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', about='" . $_POST['about'] . "', sex='" . $_POST['sex'] . "', dob='" . $_POST['dob'] . "', skype='" . $_POST['skype'] . "', facebook='" . $_POST['facebook'] . "', twitter='" . $_POST['twitter'] . "',googleplus='" . $_POST['googleplus'] . "', instagram='" . $_POST['instagram'] . "' WHERE id = {$_SESSION['id']}";
        $query_result = mysql_query($query);

        header("Location: index.php");
        exit;
    }

}


?>
<?php if(!empty($error)) {
    echo '<script type="text/javascript">alert("' . $error . '");</script>';
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Gmail - Facebook Style AJAX Chat Demo - Zechat</title>
    <meta name="keywords" content="php chat script, php ajax Chat,facebook similar chat, php mysql chat, chat script, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox, facebook similar inbox, facebook like chat" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <meta name="author" content="Zechat - Codentheme.com">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="16x16">
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <style>
        .middle-container {
            clear: both;
            padding: 50px 0px;
        }.middle-dabba {
             margin: 0px auto;
             border-radius: 5px;
             background: none repeat scroll 0% 0% #FFF;
             border-top: 5px solid #337ab7;
             padding: 45px 50px;
             box-shadow: 0px 2px 2px -1px rgba(0, 0, 0, 0.11);
         }.middle-dabba h1 {
              font: 45px/45px 'clan-thinthin',Helvetica,sans-serif;
              margin-bottom: 25px;
              font-weight: bold;
              letter-spacing: -2px;
              color: #337ab7;
              text-align: left;
              margin-top: 0px;
          }#post-form {
               margin: 0 auto;
               margin-top: 0px;
               margin-bottom: 0px;
               padding: 30px;
           }
        #post-form{
            /*width: 50%;*/
        }
        form {
                 margin: 10px 0 0 0;
             }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #666;
        }
        input, select {
            width: 95%;
        }
        input, textarea {
            background: none repeat scroll 0 0 #FFFFFF;
            color: #545658;
            border: 2px solid #C9C9C9 !important;
            padding: 8px;
            font-size: 14px;
            border-radius: 2px 2px 2px 2px;
        }
        input, textarea {
            font: 14px/24px Helvetica, Arial, sans-serif;
            color: #666;
        }
        #send p {
                  margin-bottom: 20px;width: 50%;
              }
        .middle-dabba p {
            margin: 0px 0px 24px;
            color: #7D7D7D;
            letter-spacing: 0.03em;
            text-rendering: optimizelegibility;
            font: 18px/30px 'bentonsanslight',Helvetica,sans-serif;
        }
        .input{padding: 12px;}
    </style>




<!--\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->

    <!--CHAT BOX CSS Start-->
    <link type="text/css" rel="stylesheet" media="all" href="chatcss/chat-light.css" />
    <link type="text/css" rel="stylesheet" media="all" href="chatcss/chat.css" />
    <link type="text/css" rel="stylesheet" media="all" href="chatcss/screen.css" />
    <!--[if lte IE 7]>
    <link type="text/css" rel="stylesheet" media="all" href="chatcss/screen_ie.css" />
    <![endif]-->
    <!--CHAT BOX CSS END-->

<!--\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->


</head>

<body>

<!--/*Loader start*/-->
<div class="Dboot-preloader text-center">
    <img src="img/loader.gif" width="400"/>
</div>

<div class="entry-board J_entryBoard">
    <div class="container">
        <ul class="external-entries">
            <li class="entry"><a href="index.php" target="_self">My Profile</a></li>
        </ul>

        <div class="account-info ">
            <div class="sign-entries" id="J_signEntries" style="display: block;">
                <ul>
                    <li class="entry">
                        <a href="inbox.php">Facebook Like Inbox</a>
                    </li>
                    <li class="entry">
                        <a href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div align="center" style="color: #fff; background-color: #778492; padding: 5px;">
    <div class="container"><span style="text-align: center;">Note: Please load the following links in different browsers with2 different USERID For Testing:</span></div>
</div>
<!--/*Loader END*/-->

<!-- ******HEADER****** -->
<header class="header">
    <div class="container">
        <div class="profile-picture medium-profile-picture mpp XxGreen pull-left">
            <img width="169px" style="min-height:170px;" src="storage/user_image/small<?php echo $sesuserpic; ?>" alt="<?php echo $_SESSION['username'];?>">
        </div>

        <div class="profile-content pull-left">
            <h1 class="name"><?php echo $row1['name'];?></h1>
            <h2 class="desc">#<?php echo $_SESSION['username'];?></h2>
            <ul class="social list-inline">
                <?php if(!empty($row1['facebook'])){?>
                    <li><a href="<?php echo $row1['facebook'];?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                <?php } ?>
                <?php if(!empty($row1['twitter'])){?>
                    <li><a href="<?php echo $row1['twitter'];?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                <?php } ?>
                <?php if(!empty($row1['googleplus'])){?>
                    <li><a href="<?php echo $row1['googleplus'];?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                <?php } ?>
                <?php if(!empty($row1['instagram'])){?>
                    <li><a href="<?php echo $row1['instagram'];?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                <?php } ?>
            </ul>
        </div><!--//profile-->
        <a class="btn btn-cta-primary pull-right" href="edit_profile.php"><i class="fa fa-paper-plane"></i> Edit Profile</a>
    </div><!--//container-->
</header><!--//header-->

<div class="middle-container container">
    <div class="middle-dabba col-md-12">
        <h1>Edit Your Profile</h1>
        <div id="post-form" style="padding:10px">

            <form name="form1" method="post" action="" id="send" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="input text">
                        <label for="name">Fullname </label><input type="text" name="name" value="<?php echo $row1['name'];?>">
                    </div>

                    <div class="input text">
                        <label for="dob">Date of Birth </label><input type="text" name="dob" placeholder="Format : 02-April-1992" value="<?php echo $row1['dob'];?>">
                    </div>

                    <div class="input text">
                        <label for="file">Change Profile Picture </label>
                        <img class="pull-left" src="storage/user_image/<?php echo $sesuserpic; ?>" alt="<?php echo $_SESSION['username'];?>"  style="width: 42px; border-radius: 50%"/>
                        <input type="file" name="file" style="width:86%">
                    </div>

                    <div class="input text">
                        <label for="about">About Me </label>
                        <textarea name="about" style="width: 95%;height: 137px"><?php echo $row1['about'];?></textarea>
                    </div>

                    <div class="input text">
                        <label for="sex">Sex</label>
                        <input type="radio" name="sex" value="male" style="width: 10%" <?php if($row1['sex'] == "male") { echo "checked"; }?>> Male <br>
                        <input type="radio" name="sex" value="female" style="width: 10%" <?php if($row1['sex'] == "female") { echo "checked"; }?>> Female

                    </div>
                </div>


                <div class="col-md-6">
                    <div class="input text">
                        <label for="email">Email</label><input type="text" name="email" value="<?php echo $row1['email'];?>">
                    </div>

                    <div class="input text">
                        <label for="skype">Skype ID</label><input type="text" name="skype" value="<?php echo $row1['skype'];?>">
                    </div>

                    <div class="input text">
                        <label for="facebook">Facebook</label><input type="text" name="facebook" value="<?php echo $row1['facebook'];?>">
                    </div>

                    <div class="input text">
                        <label for="googleplus">Google Plus</label><input type="text" name="googleplus" value="<?php echo $row1['googleplus'];?>">
                    </div>

                    <div class="input text">
                        <label for="twitter">Twitter</label><input type="text" name="twitter" value="<?php echo $row1['twitter'];?>">
                    </div>

                    <div class="input text">
                        <label for="instagram">Instagram</label><input type="text" name="instagram" value="<?php echo $row1['instagram'];?>">
                    </div>
                </div>
            </div>
            <div class="col-md-12" align="center">
                <button class="btn btn-cta-theme" type="submit" name="Submit">Save</button>
            </div>

            </form>
        </div>
    </div>
</div>


<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Developed with <i class="fa fa-heart"></i> by <a href="http://www.byweb.online" target="_blank">Deven Katariya</a> for developers</small>
    </div><!--//container-->
</footer><!--//footer-->



<script>
    $(window).load(function() {
        $('.Dboot-preloader').addClass('hidden');
    });
</script>
<script>
    var jQuery1111 = jQuery.noConflict();
    window.jQuery = jQuery1111;
</script>
<script type="text/javascript" src="chatjs/jquery.js"></script>
<script type="text/javascript" src="chatjs/chat.js"></script>
<script type="text/javascript" src="chatjs/lightbox.js"></script>



<div id="drupalchat-wrapper">
    <div id="drupalchat" style="">
        <div class="item-list" id="chatbox_chatlist">
            <ul id="mainpanel">
                <li id="chatpanel" class="first last">
                    <div class="subpanel" style="display: block;">
                        <div class="subpanel_title" onclick="javascript:toggleChatBoxGrowth('chatlist')" >Chat<span class="options"></span>
                            <span class="min localhost-icon-minus-1"><i class="fa fa-minus-circle minusicon text-20" aria-hidden="true"></i></span>
                        </div>
                        <div>
                            <div class="chat_options" style="background-color: #eceff1;" >
                                <div class="drupalchat-self-profile">
                                    <div class="drupalchat-self-profile-div">
                                        <div class="drupalchat-self-profile-img + localhost-avatar-sprite-28 <?php echo strtoupper($string[0]); ?>_3">
                                            <?php if(!empty($row1['picname'])) {?>
                                                <img src="storage/user_image/small<?php echo $row1['picname']; ?>"/>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="drupalchat-self-profile-namdiv">
                                        <a class="drupalchat-profile-un drupalchat_cng"><?php echo $string; ?></a>
                                    </div>

                                </div>

                            </div>
                            <div class="drupalchat_search_main chatboxinput" style="background:#f9f9f9">
                                <div class="drupalchat_search" style="height:30px;">
                                    <input class="drupalchat_searchinput live-search-box" placeholder="Type here to search" value="" size="24" type="text">
                                    <input class="searchbutton" value="" style="height:30px;border:none;margin:0px; padding-right:13px; vertical-align: middle;" type="submit"></div>
                            </div>
                            <div class="contact-list chatboxcontent">
                                <ul class="live-search-list">
                                    <?php

                                    $query = "SELECT * FROM userdata where id != '".$_SESSION['id']."' order by online = 0 , online";
                                    $result = mysql_query($query);
                                    while ($row = mysql_fetch_assoc($result)) {
                                        $id = $row['id'];
                                        $username = $row['username'];
                                        $picname = $row['picname'];


                                        $res = mysql_query("SELECT * FROM `userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;");
                                        if($res === FALSE) {
                                            die(mysql_error()); // TODO: better error handling
                                        }
                                        $num = mysql_num_rows($res);
                                        if($num == "0")
                                            $onofst = "Online";
                                        else
                                            $onofst = "Offline";

                                        ?>


                                        <li class="iflychat-olist-item iflychat-ol-ul-user-img iflychat-userlist-room-item chat_options">
                                            <div class="drupalchat-self-profile">
                                                <span title="<?php echo $onofst ?>" class="<?php echo $onofst ?> statuso" style="text-align: right"><span class="statusIN"><i class="fa fa-circle" aria-hidden="true"></i></span></span>
                                                <div class="drupalchat-self-profile-div">
                                                    <div class="drupalchat-self-profile-img + localhost-avatar-sprite-28 <?php echo strtoupper($username[0]); ?>_3">
                                                        <?php if(!empty($row['picname'])) {?>
                                                            <img src="storage/user_image/small<?php echo $row['picname']; ?>"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="drupalchat-self-profile-namdiv">
                                                    <a class="drupalchat-profile-un drupalchat_cng" href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $username ?>','<?php echo $id ?>','<?php echo $sesuserpic; ?>','<?php echo $onofst ?>')"> <?php echo $username ?></a>
                                                </div>

                                            </div>

                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>


<script type="text/javascript">
    //ScrollDown Function
    function scrollDown2(chatboxtitle){
        var wtf    = jQuery1111("#chatbox_"+chatboxtitle+" .chatboxcontent");
        var height = wtf[0].scrollHeight;
        wtf.scrollTop(height);
    }

    // Upload Images/Files Function
    function uploadimage(touname) {

        var file_name=jQuery1111("#chatbox_"+touname+" #imageInput").val();
        var fileName = jQuery1111("#chatbox_"+touname+" #imageInput").val();
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

        var toid = jQuery1111("#chatbox_"+touname+" #to_id").val();
        var tun = jQuery1111("#chatbox_"+touname+" #to_uname").val();
        var fun = jQuery1111("#chatbox_"+touname+" #from_uname").val();

        var base_url = 'process.php?toid='+toid+'&tun='+tun+'&fun='+fun;
        var file_data=jQuery1111("#chatbox_"+touname+" #imageInput").prop("files")[0];
        var form_data=new FormData();
        form_data.append("file",file_data);

        jQuery1111('#loadmsg_'+touname).show();      // Loader show

        jQuery1111.ajax({
            type:"POST",
            url: base_url,
            cache:false,
            contentType:false,
            processData:false,
            data:form_data,
            success:function(data){
                //alert(data);
                jQuery1111.each(data.items, function(i,item){
                    if (item)	{ // fix strange ie bug
                        chatboxtitle = item.chatboxtitle;
                        filename = item.filename;
                        path = item.path;

                        jQuery1111('#loadmsg_'+chatboxtitle).hide();     // Loader hide

                        var message_content = "<a url='"+path+"' onclick='trigq(this)'><img src='"+filename+"' style='max-width:156px;min-height:100px;padding: 4px 0 4px 0; border-radius: 7px;cursor: pointer;'/></a>";
                        jQuery1111("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+item.sender+'</span></div><img class="direct-chat-img" src="storage/user_image/'+img+'" alt="message user image"><span class="direct-chat-text">'+message_content+'</span></div>');
                    }

                    setTimeout(scrollDown2(chatboxtitle), 5000);
                });
            },
            error:function(){
                //----------
            }
        });
    }

    //Search User In contactList
    jQuery(document).ready(function(){
        jQuery1111('.live-search-list li').each(function(){
            jQuery1111(this).attr('data-search-term', jQuery1111(this).text().toLowerCase());
        });
        jQuery1111('.live-search-box').on('keyup', function(){
            var searchTerm = jQuery1111(this).val().toLowerCase();
            jQuery1111('.live-search-list li').each(function(){
                if (jQuery1111(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                    jQuery1111(this).show();
                } else {
                    jQuery1111(this).hide();
                }
            });
        });
    });
</script>

<!--This div for modal light box chat box image-->

<table id="lightbox"  style="display: none;height: 100%">
    <tr>
        <td height="10px">
            <p>
                <img src="https://www.itroteam.com/wp-content/plugins/itro-wordpress-marketing/images/close-icon-white.png" width="30px" style="cursor: pointer"/>
            </p>
        </td>
    </tr>
    <tr>
        <td valign="middle">

            <div id="content">
                <img src="#" />
            </div>
        </td>
    </tr>
</table>
<!--This div for modal light box chat box image-->

</body>
</html>