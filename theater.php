
<?php 






$GLOBALURL = "https://app.kaseify.com/";
$filesurlsourcer = "https://app.kaseify.com/ccache";

session_start();

// Report all PHP errors (see changelog)
error_reporting(E_ALL);

require_once('includes/apiserv.php'); 
require_once('includes/servset.php'); 


$cookie_name = "loggedin";
if (!isset($_COOKIE[$cookie_name]))
{
    header("Location: index.php");
}
else
{
    $cookie_value = $_COOKIE[$cookie_name];   
}

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: ".mysqli_connect_error());
}

$query1 = "SELECT * FROM userdata where id = '".$_SESSION['id']."'";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_array($result1);
$string = $row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "") {
    $sesuserpic = "avatar_default.png";
}



////////// Get User Info //////////////
    
$sql = "SELECT * FROM users WHERE email='$cookie_value'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $theid = $row["id"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $uzfolder = $row["folder"];
         
    }
} 



///////// USER FTP FUNCTION /////////////

//////////// FTP Function //////////
    
  


///////// File changer ////////

if (isset($_GET['fileidgrabber']))
{
    
   $fileidgraberquerer = $_GET['fileidgrabber'];
       
    $sqlfileidgraberquerer = "SELECT * FROM files WHERE id='$fileidgraberquerer'";
$resultfileidgraberquerer = $conn->query($sqlfileidgraberquerer);

                            if ($resultfileidgraberquerer->num_rows > 0) {
                                // output data of each row
                                while($row = $resultfileidgraberquerer->fetch_assoc()) {
                                    $filefileidgraberquerer= $row["name"];
                                    

                                }



                            }     
      
    
}
















///// GET CASE INFO /////////////
if (isset($_GET['caseid']))
{
    
    
$caseidz = $_GET['caseid'];
        
$sql = "SELECT * FROM cases WHERE id='$caseidz'";
$result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    $theidcase = $row["id"];
                                    $cname = $row["cname"];
                                    $pname = $row["patientname"];
                                    $creatorid = $row["creatorid"];
                                    $crdate = $row["crdate"];
                                    $patientage = $row["age"];
                                    $cnamethumber = $row["thumbnail"];
                                    

                                }



                            }   
    
    
$sql6 = "SELECT * FROM users WHERE id='$creatorid'";
$result6 = $conn->query($sql6);

                            if ($result6->num_rows > 0) {
                                // output data of each row
                                while($row = $result6->fetch_assoc()) {
                                    $crfname = $row["fname"];
                                    $crlname = $row["lname"];
                                    

                                }



                            }   
    
    
} 






if (isset($_GET['file']))
{ 

$thefileid = $_GET['file'];
    
    
$sql11 = "SELECT * FROM files WHERE id='$thefileid'";
$result11 = $conn->query($sql11);

                            if ($result11->num_rows > 0) {
                                // output data of each row
                                while($row = $result11->fetch_assoc()) {
                                    $filenamename1 = $row["name"];
                                   
                                    

                                                                        }



                                                        }   
    
    
    
    
    
    
}





?>





<html>


<head>
    
    
    <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">


<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,900itallic" rel="stylesheet">
<script src="scriptoz.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
   
<link rel="stylesheet" type="text/css" href="assets/style.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    
    
      <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!--javascript files-->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
  
  
    
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="document-library/css/style.css">
    <link rel="stylesheet" href="stylezer.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="document-library/libs/yepnope.1.5.3-min.js"></script>
    <!-- <script type="text/javascript" src="document-library/ttw-document-library.min.js"></script> -->
    <script type="text/javascript" src="document-library/src/ttw-document-library.js"></script>
    <script type="text/javascript" src="document-library/src/ttw-document-viewer.js"></script>
    <script type="text/javascript" src="document-library/src/ttw-invisible-dom.js"></script> 
    <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="chat/chatjs/chat.js"></script>

    <script>
    
    $(document).ready(function() {
        
        $("#userara").submit(function() { 
        $.post('ajaxposter.php',$('#userara').serialize(), function(data) {
          $(".noteswrapper").append('<div class="notebubbleblue">'+data+'</div>');
        });
        return false;
        });
        
    });
    
    $(document).ready(function(){
$('.rapper').fadeIn(2000);
});    
    </script>
    
<link type="text/css" rel="stylesheet" media="all" href="chat/chatcss/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="chat/chatcss/screen.css" />
 
<!--[if lte IE 7]>
<link type="text/css" rel="stylesheet" media="all" href="chatcss/screen_ie.css" />
<![endif]-->
    
    <title> Kaseify - <?php echo $cname;?> </title>
    
  
    
</head>
    
    
    
    <body>
          
        
          
       
        
        
        
        
        
        
        
        
        
        
        
   
        <div class="topbar"> <!-- TOP BAR BEGINS -->

      
 
    
            
            <div class="Premenubar">
                <div class="iconsright"> 
            <i class="fa fa-bell fa-lg bellstyle" aria-hidden="true"></i>
                </div>
                
                   
                
                 
                <div class="dropdown">
                    
                    
                <div class="menuhead" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
      <button class="leftmenulog" >  <h5> <?php echo $fname; ?> <?php echo $lname; ?></h5>  </button> <b class="caret"></b>
            
                </div>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><center> <img src="https://www.rsmuk.com/~/media/images/staffdetailsphotos/website_avatar2.jpg?la=en&hash=5BE90D6617489EFF9509D2065D7D26040DC86B67" alt="" class="profilepic"> <br /><a href="#"><?php echo $fname; ?> <?php echo $lname; ?></a> </center></li>
      
      <li role="separator" class="divider"></li>
    <li> <a href="profile.php"><i class="fa fa-cog padfsome" aria-hidden="true"></i>Account settings</a></li>
    <li> <a href="#"><i class="fa fa-users padfsome" aria-hidden="true"></i>Collaborators</a></li>
    <li> <a href="#"><i class="fa fa-question-circle padfsome" aria-hidden="true"></i>

 Help</a></li>
    <li><a href="logout.php"><i class="fa fa-key padfsome" aria-hidden="true"></i>Logout</a></li>

  </ul>
                    
                    
                    
                    
</div> <!-- Dropdown -->
            
            </div>
            
                <div  data-toggle="modal" data-target="#squarespaceModal" class="addfilebutton">
                    
                    
                    <div class="btn-group btn-group-justified demoPadder" role="group" aria-label="Justified button group">
   
                        
      <a href="#" class="btn btn-default topfilup" role="button">Upload file</a>
    
                        
    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
            </div>
            
            
            
            <div class="logo">
                    <a href="personal.php">   <img class="logotarget" src="images/logo.png" height="50" width="130" /> </a>
            </div>

            
       
            
            
            
            
            
        </div> <!-- TOP BAR ENDS -->
        
    
        

                    

                      
                        
        <div class="rapper"> 
       

                        
       <div class="topspace">
        
        
                <div class="row">
                     
                                                 
                                              <div class="col-md-9 nopaiz">
                                                  
                                                <script type="text/javascript">
        $(function() {




            var documentList =  [
               <?php      
                
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: ".mysqli_connect_error());
} 

                                       
$sqlfiles = "SELECT * FROM files WHERE caseid='$theidcase'";
$resultfiles = $conn->query($sqlfiles); 
    
    $doc="true";
                
                
if ($resultfiles->num_rows > 0) {
         

    // output data of each row
    while($row = $resultfiles->fetch_assoc()) {
        $filenamercaza = $row["name"];
        $filetypeer = $row["type"];
        $fileidgrabberz = $row["id"];
        $fileupdoc = $row["updoc"];
        $uptime = $row['uptime'];
        $date = DateTime::createFromFormat("Y-m-d H:i:s", $uptime);

        $sqlDoctorName = "SELECT * FROM users WHERE id='$fileupdoc'";
        $result6 = $conn->query($sqlDoctorName);
      
        echo "{\n"; 
        echo " \"path\": \"ccache/".$filenamercaza."\"\n"; 
        if( $filetypeer =='doc'|| $filetypeer == 'docx'){
            echo ", \"show\": ".$doc."\n"; 
        }
        if ($result6->num_rows == 1) {
            // output data of each row
            $row2 = $result6->fetch_assoc();
            //var_dump($row2);
            $docfname = $row2["fname"];
            $doclname = $row2["lname"];
            echo ", \"name\": \"" . $docfname . " " . $doclname . "\"\n";
        }
        echo ", \"id\": " . $fileupdoc . "\n";
        echo ", \"date\": " . $date->getTimeStamp() . "\n";
        echo "},\n";
    } 
} 
?>       
                
            ];


            console.log(documentList);
            /*$.each(documentList, function(i, val) {
                console.log(documentList[i]);
                documentList[i].details.id = documentList[i].id;
                documentList[i].details.date = documentList[i].date; 
            });*/
            console.log(DocumentLibrary);
            var library = new DocumentLibrary({
                $anchor: $('#sidebar'),
                $openItemAnchor:$('#document-content'),
                listType:'tiles',
                openItemWidth:'100%'
            });
            library.load(documentList);

        });

    </script>   
                                                

                                        
<div id="wrapper">
    <div id="sidebar"></div>
    <div id="document-content"></div>
</div>


                                                    
                                                    
                                                  
                                                    
                                                    </div>

                                              
        
                                     

                                            <div class="col-md-3 col-sm-4 rightpanel">


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
                                    $result = mysqli_query($conn, $query);
                                    var_dump($result);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['id'];
                                        $username = $row['username'];
                                        $picname = $row['picname'];

                                        //var_dump("SELECT * FROM `userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW())");
                                        $res = mysqli_query($conn, "SELECT * FROM `userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;") or die(mysqli_error());;
                                        $num = mysqli_num_rows($res);
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
                                               
                                                
                                     <div class="col-lg-12 notesheadbarfirst"> 
                                                
                                                    <h5> Case info : </h5>
                                                    
                                                    
                                                </div>
                                                
                                                 <div class="patientphotoright">
                                                
                                                <img src="cthumbs/<?php echo $cnamethumber; ?>">
                                                
                                                
                                                </div>
                                                
                                                
                                                <div class="cazatiteul"> 
                                                    <div class="rightpanelwrapper">
<center> 
                                            Case name:    <?php  echo $cname; ?> <br />
                                            Created By  :<?php echo $crfname; ?> <?php echo $crlname; ?> <br />
                                            Patient name :  <?php echo $pname;?> <br />
                                            Patient Age :  <?php echo $patientage;?> <br />
</center>
                                                    </div>
                                                    </div>
                                                
                                                
                                             <div class="col-lg-12 notesheadbar"> 
                                                
                                                    <h5> Case settings : </h5>
                                                    
                                                    
                                                </div>
                                            
                                                
                                                <div class="sharesetz">
                                                    <center> 
                                           <div class="col-lg-12 col-mg-12 col-xs-12">
                                               
                                               
                                               
                                               
                                               
                                               <button  data-toggle="modal" data-target="#squarespaceModalsharer" type="button" class="btn btn-info kasebut">Share case</button>
                                               
                                               
                                               
                                                </div>
                                                    
                                                <div class="col-lg-12 col-mg-12 col-xs-12">
                                               <button type="button" class="btn btn-info kasebut">Add case note</button>
                                               
                                               
                                               
                                                </div>
                                                <div class="col-lg-12 col-mg-12 col-xs-12">
                                               <button type="button" class="btn btn-info kasebut">Chat with collaborators</button>
    
        
        
                                               
                                                
                                                
                                            </div>
                                            </center>
                                            </div>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                           
                            
                                                
                                                
                                                
                                                
                                                
                                       
                                                
                                               
                                                
                                                <div class="col-lg-12 notesheadbar"> 
                                                
                                                    <h5> Notes : </h5>
                                                    
                                                    
                                                </div>
                                                    
                                                <div class="row">
 
  <div class="col-lg-12 inputhider">
      <form id="userara"> 
    <div class="input-group">
      <input type="text" name="messages" class="form-control" placeholder="Type your note here...">
    <input type="hidden" name="theid" value="<?php echo $theid; ?>" />
    <input type="hidden" name="caseid" value="<?php echo $caseidz; ?>" />
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit">Send!</button>
      </span>
    </div><!-- /input-group -->
          </form>
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
                                                
                                                <div class="noteswrapper"> 
                                                    <?php
$sqlnotes = "SELECT * FROM notes WHERE caseid='$caseidz'";
$resultnotes = $conn->query($sqlnotes);                                                                                 
     if ($resultnotes->num_rows > 0) {
    // output data of each row
    while($row = $resultnotes->fetch_assoc()) {
        $notebody = $row["body"];
        $notesenderid = $row["senderid"];
  
  
        echo "<div class=\"notebubbleblue\">\n"; 
        echo $notebody;
        echo "</div>\n";
        
        
        $sqlnotesenderid = "SELECT * FROM users WHERE id='$notesenderid'";
        $resultnotesuserid = $conn->query($sqlnotesenderid);  
        foreach ($resultnotesuserid as $key => $value)
{
   echo "Sender : " . $value['fname'] ."  " . $value['lname'];
  
   
    
}
        
    
        
        
       
            
}}    else {
        echo "<center>There is no notes</center>";
    }
                                                    
            ?>                                        
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                
                                                
                                                </div>
                                                </div>
                                                </div>
                                                </div>
  
                        
                  
                 

                                                  
                               

       

        
        
        <!-- line modal -->
    <div class="newcasepopup"> 
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="lineModalLabel">Add new case files</h3>
        </div>
        <div class="modal-body">
            
            <!-- content goes here -->
            <form  method="post" action="uploadapi.php?caseid=<?php echo $theidcase;?>" enctype="multipart/form-data" >
            
              <div class="form-group">
                <label for="exampleInputFile">Case files upload</label>
                <h5> You can select multiple files</h5>
                <input name="files[]" type="file" multiple>
                 
                
              </div>
              <div class="checkbox">
               
              </div>
             
           

        </div>
        <div class="modal-footer">
            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
                </div>
                <div class="btn-group btn-delete hidden" role="group">
                    <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Upload</button>
                </div>
            </div>
        </div>
             </form>
    </div>
  </div>
</div>
        
        </div>
        
   

  
</div>
        
        
        
        
        
        
        
        
                    <!-- line modal shares-->
        <div class="newcasepopup"> 
<div class="modal fade" id="squarespaceModalsharer" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="lineModalLabel">Share case</h3>

        </div>
        <div class="modal-body">
            <p>Enter one of the following recipient info</p>
            <!-- content goes here -->
            <form name="shareform" method="post" action="sharesender.php" enctype="multipart/form-data">
              <div class="form-group">
                <label for="exampleInputEmail1">Recipient email</label>
                <input name="recemail" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter the email here...">
              </div>
                
                <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input name="recusername" type="text" class="form-control" id="exampleInputEmail1" placeholder="enter the recipient username">
              </div>
              <div class="form-group">
              
                  <label for="exampleInputPassword1"></label>
                 <div class="form-group">
  <label for="exampleInputPassword1">Select a case:</label>
  <select name="caseoption" class="form-control" id="sel1">
      
      
      
      <?php
         
    $conn = mysqli_connect($servername, $username, $password, $database);
$sqlformoptions = "SELECT * FROM cases WHERE creatorid='$theid'";
$result3options = $conn->query($sqlformoptions);

if ($result3options->num_rows > 0) {
    // output data of each row
    while($row = $result3options->fetch_assoc()) {
        
        
        $casename = $row["cname"];
       
echo "<option>". $casename . "</option>\n";

      
     }}
      
      ?>

  </select>
</div>
                  
                  
                  
                  
                  
                  
              </div>
                
                <div class="form-group">
                <div class="radio">
  <label><input type="radio" name="optradio">Patient</label>
</div>
<div class="radio">
  <label><input type="radio" name="optradio">Doctor</label>
</div>
              </div>
                
                
            
        </div>



        <div class="modal-footer">
            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
                </div>
                <div class="btn-group btn-delete hidden" role="group">
                    <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="submit" role="button">Share !</button>
                </div>
            </div>
        </div>
        
        </form>
        
        
    </div>
  </div>
</div>
        
        </div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
         <?php   $conn->close(); ?>
        
        
      
    
    
    
    

    
    
    
    
    
    
    
    
    
        
                                            
  <script type="text/javascript">

</script>  
    
    </div>
    
    
    
    
    
  
    
    
    
    
    
    
    
    </body>

    
    
    
    
    
    
     
                
</html>