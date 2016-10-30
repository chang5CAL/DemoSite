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

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Gmail - Facebook Style AJAX Chat Demo - Zechat</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="php chat script, php ajax Chat,facebook similar chat, php mysql chat, chat script, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox, facebook similar inbox, facebook like chat" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <meta name="author" content="Zechat - Codentheme.com">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="16x16">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


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
    <!--COPY-Restrict JS YOU Can Remove This Will No effect-->
    <!-- <script src="chatjs/copyRestrict.js"></script> -->
    <style>
        /*Loader start*/

        .hidden {
            display: none!important;
            visibility: hidden!important;
        }
        .text-center {
            text-align: center;
        }
        .Dboot-preloader {
            /* padding-top: 20%; */
            background-color: #fff;
            display: block;
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 999999999999999;
        }

        /*Loader end*/

    </style>
</head>

<body onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);">
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

<div class="container sections-wrapper">
    <div class="row">
        <div class="primary col-md-8 col-sm-12 col-xs-12">
            <section class="about section">
                <div class="section-inner">
                    <h2 class="heading">About Me</h2>
                    <div class="content">
                        <?php echo $row1['about'];?>

                    </div><!--//content-->
                </div><!--//section-inner-->
            </section><!--//section-->

        </div><!--//primary-->
        <div class="secondary col-md-4 col-sm-12 col-xs-12">
            <aside class="info aside section">
                <div class="section-inner">
                    <h2 class="heading sr-only">Basic Information</h2>
                    <div class="content">
                        <ul class="list-unstyled">
                            <?php
                            if(!empty($row1['sex']))
                            {
                                if($row1['sex'] == "male")
                                {
                                    ?><li><i class="fa fa-mars"></i><span class="sr-only">Sex:</span>Male</li><?php
                                }
                                else{
                                    ?><li><i class="fa fa-venus"></i><span class="sr-only">Sex:</span>Female</li><?php
                                }
                            }
                            ?>


                            <li><i class="fa fa-birthday-cake"></i><span class="sr-only">DOB:</span><?php echo $row1['dob'];?></li>
                            <li><i class="fa fa-map-marker"></i><span class="sr-only">Location:</span><?php echo $row1['country'];?></li>
                            <li><i class="fa fa-envelope-o"></i><span class="sr-only">Email:</span><a href="#"><?php echo $row1['email'];?></a></li>
                            <li><i class="fa fa-skype"></i><span class="sr-only">Skype ID:</span><a href="#"><?php echo $row1['skype'];?></a></li>
                        </ul>
                    </div><!--//content-->
                </div><!--//section-inner-->
            </aside><!--//aside-->



        </div><!--//secondary-->
    </div><!--//row-->
</div><!--//masonry-->

<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Developed with <i class="fa fa-heart"></i> by <a href="http://www.byweb.online" target="_blank">Deven Katariya</a> for developers</small>
    </div><!--//container-->
</footer><!--//footer-->

<!-- Javascript -->
<script type="text/javascript" src="assets/plugins/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-rss/dist/jquery.rss.min.js"></script>
<!-- custom js -->
<script type="text/javascript" src="assets/js/main.js"></script>






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

                                        //var_dump("SELECT * FROM `userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW())");
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

