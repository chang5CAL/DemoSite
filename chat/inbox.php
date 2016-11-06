<?php
require_once('/includes/config.php');

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: ".mysqli_connect_error());
}

if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$query1 = "SELECT * FROM userdata where id = '".$_SESSION['id']."'";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
$row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "")
    $sesuserpic = "avatar_default.png";
?>
<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/favicon.png" type="image/png" sizes="16x16">
    <title>Facebook Style Message INBOX Script Demo - Zechat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="php chat script, php ajax Chat, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <link rel="stylesheet" href="chatcss/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="inbox/css/reset.css">
    <link rel="stylesheet" href="inbox/css/style.css">


    <!--start Toggle for smiley -->
    <!--COPY-Restrict JS YOU Can Remove This Will No effect-->
    <script src="chatjs/copyRestrict.js"></script>
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
<!--/*Loader end*/-->
<div class="entry-board J_entryBoard">
    <div>
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
    <div><span style="text-align: center;">Note: Please load the following links in different browsers with2 different USERID For Testing:</span></div>
</div>
<!--/*Loader END*/-->



<div class="wrapper">
    <div class="container">
        <div class="left">
            <div class="top">
                <input type="text" class="live-search-box" placeholder="search here" />
                <a href="javascript:;" class="search"></a>
            </div>
            <ul class="live-search-list people" style="overflow-y:scroll; height:484px;overflow-x:hidden">

                <?php
                $query = "SELECT * FROM userdata where id != '".$_SESSION['id']."' order by online = 0 , online";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $username = $row['username'];
                    $picname = $row['picname'];
                    if($picname == "")
                        $picname = "avatar_default.png";
                    else{
                        $picname = "small".$picname;
                    }
                    $res = mysqli_query($conn, "SELECT * FROM `userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;") or die(mysqli_error());
                    $num = mysqli_num_rows($res);
                    if($num == "0")
                        $onofst = "Online";
                    else
                        $onofst = "Offline";
                    ?>

                    <li id="chatbox1_<?php echo $username ?>" class="person" data-chat="person_<?php echo $id ?>" href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $username ?>','<?php echo $id ?>','<?php echo $picname; ?>','<?php echo $onofst ?>')">
                        <div class="chatboxhead">
                    <span class="userimage">
                       <img class="direct-chat-img" src="storage/user_image/<?php echo $picname; ?>" alt="<?php echo $username ?>" />
                    </span>
                            <span class="bname name"><?php echo $username ?></span>
                            <span class="time <?php echo $onofst ?>"><i class="fa fa-circle" aria-hidden="true"></i></span>
                    <span class="hidecontent">
                        <input id="to_id" name="to_id" value="<?php echo $id ?>" type="hidden">
                        <input id="to_uname" name="to_uname" value="<?php echo $username ?>" type="hidden">
                        <input id="from_uname" name="from_uname" value="<?php echo $row1['username']; ?>" type="hidden">
                    </span>
                            <span class="preview project">Web Developer</span>
                        </div>
                    </li>
                <?php } ?>

            </ul>
        </div>
        <div class="right" id="right">
            <div class="top chatboxhead"><span style="float: right">Project: <span class="project">Title</span></span><span style="float: left">
                    <span class="userimage"><img src="storage/user_image/avatar_40x40.png" alt="image"></span>
                    <span class="name">Name</span></span>
            </div>

            <div id="resultchat">
                <img id="loader" src='http://www.flexiglide.co.uk/img/loading.gif'>
                <!-- Here chating messages content will be show by inbox.JS-->
            </div>

            <img id="loadmsg" src="img/chatloading.gif">
        </div>
    </div>
</div>


<script type="text/javascript" src="chatjs/jquery.js"></script>
<script type="text/javascript" src="chatjs/lightbox.js"></script>
<script type="text/javascript" src="chatjs/inbox.js"></script>
<script type="text/javascript" src="inbox/js/index.js"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>


<!--start Toggle for smiley -->
<script type = "text/javascript" language = "javascript">

    $(document).ready(function() {
        $(".embtn").click(function(event){
            var client = $('.chat.active-chat').attr('client');
            var prevMsg = $("#chatbox_"+client+" .chatboxtextarea").val();
            var emotiText = $(event.target).attr("alt");

            $("#chatbox_"+client+" .chatboxtextarea").val(prevMsg+' '+emotiText);
            $("#chatbox_"+client+" .chatboxtextarea").focus();
        });
    });

    function chatemoji() {
        $(".target-emoji").toggle( 'fast', function(){
        });
        var heit = $('#resultchat').css('max-height');
        if(heit == '458px'){
            $('#resultchat').css('max-height', '360px');
            $('#resultchat').css('min-height', '360px');
        }
        else{
            $('#resultchat').css('max-height', '458px');
            $('#resultchat').css('min-height', '458px');
        }
    }


</script>
<!--start Toggle for smiley -->

<script>
    $(window).load(function() {
        $('.Dboot-preloader').addClass('hidden');
    });
</script>

<script>
/*Get get on scroll*/
    $("#resultchat").scrollTop($("#resultchat")[0].scrollHeight);
    // Assign scroll function to chatBox DIV
    $('#resultchat').scroll(function(){
        if ($('#resultchat').scrollTop() == 0){

            var client = $('.chat.active-chat').attr('client');

            if($("#chatbox_"+client+" .pagenum:first").val() != $("#chatbox_"+client+" .total-page").val()) {

                $('#loader').show();
                var pagenum = parseInt($("#chatbox_"+client+" .pagenum:first").val()) + 1;

                var URL = 'https://app.kaseify.com/chat/chat.php?page='+pagenum+'&action=get_all_msg&client='+client;

                get_all_msg(URL);                                       // Calling get_all_msg function

                $('#loader').hide();									// Hide loader on success

                if(pagenum != $("#chatbox_"+client+" .total-page").val()) {
                    setTimeout(function () {										//Simulate server delay;

                        $('#resultchat').scrollTop(100);							// Reset scroll
                    }, 458);
                }
            }

        }
    });
/*Get get on scroll*/

//Inbox User search
    jQuery(document).ready(function($){

        $('.live-search-list li').each(function(){
            $(this).attr('data-search-term', $(this).text().toLowerCase());
        });

        $('.live-search-box').on('keyup', function(){

            var searchTerm = $(this).val().toLowerCase();

            $('.live-search-list li').each(function(){

                if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });

        });

    });

//Uploading Image And files
    function uploadimage(touname) {


        var file_name=$("#chatbox_"+touname+" #imageInput").val();
        var fileName = $("#chatbox_"+touname+" #imageInput").val();
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

        var toid = $("#chatbox_"+touname+" #to_id").val();
        var tun = $("#chatbox_"+touname+" #to_uname").val();
        var fun = $("#chatbox_"+touname+" #from_uname").val();

        var base_url = 'process.php?toid='+toid+'&tun='+tun+'&fun='+fun;

        var file_data=$("#chatbox_"+touname+" #imageInput").prop("files")[0];
        var form_data=new FormData();
        form_data.append("file",file_data);

        $('#loadmsg').show();
        var wtf    = $('#resultchat');
        var height = wtf[0].scrollHeight;
        wtf.scrollTop(height);

        $.ajax({
            type:"POST",
            url: base_url,
            cache:false,
            contentType:false,
            processData:false,
            data:form_data,
            success:function(data){

                $.each(data.items, function(i,item){
                    if (item)	{ // fix strange ie bug

                        chatboxtitle = item.chatboxtitle;
                        filename = item.filename;
                        path = item.path;

                        $('#loadmsg').hide();

                        var message_content = "<a url='"+path+"' onclick='trigq(this)' style='cursor: pointer;'><img src='"+filename+"' height='100'/></a>";
                        $("#chatbox_"+chatboxtitle).append('<div class="bubble me">'+message_content+'</div>');

                        var wtf    = $('#resultchat');
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                    }
                });
            },
            error:function(){
                //----------
            }
        });
    }

</script>
<!--This div for modal light box chat box image-->
<div id="lightbox" style="display: none;">
    <p><img src="https://www.itroteam.com/wp-content/plugins/itro-wordpress-marketing/images/close-icon-white.png" width="30px" style="cursor: pointer"/> </p>
    <div id="content">
        <img src="#" />
    </div>
</div>
<!--This div for modal light box chat box image-->

</body>
</html>
