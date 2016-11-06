<?php

























// Report all PHP errors (see changelog)
error_reporting(E_ALL);

// CALL THE CONFIGS
require_once('includes/apiserv.php'); 
require_once('includes/servset.php'); 


// LOGIN CHECK
$cookie_name = "loggedin";
if (!isset($_COOKIE[$cookie_name]))
{
	
    header("Location: index.php");
}
else
{
	$cookie_value = $_COOKIE[$cookie_name];
    

}
    

////////// Get User Info //////////////

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
	die("Database connection failed: ".mysqli_connect_error());
    }
            
    
    
$sql = "SELECT * FROM users WHERE email='$cookie_value'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $theid = $row["id"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $userfolder = $row["folder"];
        $prefix = $row["userlevel"];
        
      //Start your session
      session_start();
      //Store the name in the session
      $_SESSION['username'] = $fname;
        $_SESSION['id'] = $theid;
        
        
    }
} else {
    header("Location: index.php");
}
    


if ($prefix == 0) {
        $prefixprofiler = "Mr";  
} else if ($prefix == 1) {
        $prefixprofiler = "Doctor"; 
    
} else {
        $prefixprofiler = "GP."; 
}






//////////// FORM SENT CHECK ////////////////

    
    
    
 
    
    
    








//////////// FORM SENT CHECK ////////////////
if (isset($_POST["cname"]) && ($_POST["patientname"]) && ($_POST["age"]) && ($_FILES["fileToUpload"]["name"])) { 
    
    $key = md5(microtime().rand());
    $cazaname = $_POST["cname"];
    $pazientnam = $_POST["patientname"];
    $aga        = $_POST["age"];
    $thumb      = $_FILES["fileToUpload"]["name"];
    
    $sql = "INSERT INTO cases (cname, patientname, creatorid, crdate, thumbnail, age, sharekey)
VALUES ('$cazaname', '$pazientnam', '$theid', CURDATE(), '$thumb', '$aga', '$key')";
    
    $result = $conn->query($sql);
    
    
    
    
       //////////// FTP Function //////////
    
    
    


///////// UPLOAD FUNCTION /////////////////

    

$target_dir = "cthumbs/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$thefilename = $_FILES["fileToUpload"]["name"];


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    
    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo ".";
        
        
        
$thefolder = "User_".$userfolder."/";     
echo "<br />".$thefolder;
        
$ftp_server = "ftp.box.com";
$ftp_user_name = "brad@kaseify.com";
$ftp_user_pass = "Kaseify11!";
$destination_file = "casesthumbs/".$_FILES["fileToUpload"]["name"];
$source_file = "cthumbs/".$_FILES["fileToUpload"]["name"];



// set up basic connection
$conn_id = ftp_connect($ftp_server, 21);


// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
ftp_pasv($conn_id, true); 
// check connection
if ((!$conn_id) || (!$login_result)) { 
    echo "BOX connection has failed!";
    echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
    exit; 
} else {
    echo "Connected to $ftp_server, for user $ftp_user_name";
}




// upload the file
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 

// check upload status
if (!$upload) { 
echo "<br />BOX upload has failed!";
} 



   
        
    } else {
        echo "Sorry, there was an error uploading your file.";
    } }
    
  











    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ///////// CREATE CASE FOLDER
    
    
 


  // set the various variables
    $ftproot = "User_".$userfolder."/";
    $srcroot = "/";        
    
   
    if (!chdir($srcroot)) { echo "Could not enter local source root directory."; die(); }
    if (!ftp_chdir($conn_id,$ftproot)) { echo "Could not enter root directory."; die(); }



if (ftp_mkdir($conn_id, $cazaname)) {
 echo "successfully created $cazaname\n";
} else {
 echo "There was a problem while creating $cazaname\n";
}

    
    
    
    

    
    
    
    
    
    

    
    
    
// close the connection
ftp_close($conn_id);
    
    
    

    
    
    
    
    

   
    
    
    
    
    
    
}








































$conn->close();


?>






























<html>




<head>
    
    
    <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">


<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

    
<link rel="stylesheet" type="text/css" href="assets/style.css">
    
    
      <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <title> Kaseify</title>
    
    
      <script type="text/javascript">
$('.thumbnail').hide().fadeIn('slow');


$(document).ready(function(){
$('.rapper').fadeIn(2000);
});


      
      
      
</script> 
  
    
</head>
    
    
    
    <body>
          
   <?php
        include('topbar.php');
        
        ?>
        
        
        
        
        
        
        
        
        
        
        <div class="rapper"> 
        <div class="row">
                <div class="col-md-2 col-md-offset-3">
            
            
                    <div class="contain">
                                   <div class="container">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-2">
                                                     <form method="get" action="search.php"> 
                                                    <div id="imaginary_container"> 
                                                        <div class="input-group stylish-input-group">
                                                           
                                                            <input name="cname" type="text" class="form-control"  placeholder="Search" >
                                                            <span class="input-group-addon">
                                                                <button type="submit">
                                                                    <span class="glyphicon glyphicon-search"></span>
                                                                </button>  
                                                            </span>
                                                               
                                                        </div>
                                                    </div>
                                                          </form>
                                                </div>
                                            </div>
                                    </div>
   
                        
                        
                      
                        
                        
                        
                        
                        <div class="row">
  <div class="col-md-12">
    <div class="thumbnail">
      <div class="addcase">
        <img  data-toggle="modal" data-target="#squarespaceModal" src="images/newcase.jpg" alt="...">
        
        
        </div>
     <h4>New Case</h4>
    </div>
      
      
      
      <?php


    $conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
	die("Database connection failed: ".mysqli_connect_error());
            }
    
    
$sql3 = "SELECT * FROM cases WHERE creatorid='$theid'";
$result3 = $conn->query($sql3);

if ($result3->num_rows > 0) {
    // output data of each row
    while($row = $result3->fetch_assoc()) {
        
        
        $casename = $row["cname"];
        $caseid = $row["id"];
        $paname = $row["patientname"];
        $crdate = $row["crdate"];
        $creatorid = $row["creatorid"];
        $thumbimg   = $row["thumbnail"];
      
   
echo " <div class=\"thumbnail\">\n"; 
echo "<div data-toggle=\"modal\" data-target=\"#squarespaceModalshares\" class=\"sharebuttonplusimg\">\n"; 
        
echo "</div>\n"; 
echo "<a href=\"theater.php?caseid=" .$caseid . "\">\n";        
echo "        \n"; 
echo "            \n"; 
echo "          <img class=\"imgcase\" src=\"cthumbs/" . $thumbimg . "\">\n"; 
echo "\n"; 
echo "           \n"; 
        
echo "<div class=\"sharebuttonplus\">\n"; 
echo "\n"; 
echo "<div id=\"casehover\">Test message</div>\n";
echo "\n"; 
echo "</div>\n";
echo "<div class=\"caseinfobox\" >\n"; 
echo "     <h4>".  $casename . "</h4>\n"; 
echo "    <p> Creation date : ". $crdate ."</p>\n"; 
echo "    <p> Patient name : " . $paname . "</p>\n"; 
echo "    </div>\n";
echo "    </div>\n";
echo "</a>\n";
        
        

         
    }
} 

?>

      
      
      
      
  </div>
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
			<h3 class="modal-title" id="lineModalLabel">New case</h3>
		</div>
		<div class="modal-body">
			
            <!-- content goes here -->
			<form method="post" action="personal.php" enctype="multipart/form-data">
              <div class="form-group">
                <label for="exampleInputEmail1">Case name</label>
                <input name="cname" type="text" class="form-control" id="exampleInputEmail1" placeholder="The name of the case">
              </div>
                
               
                
                                 <div class="form-group">
  <label for="exampleInputPassword1">Select a field:</label>
  <select class="form-control" id="sel1">
      
      
      
      <?php
         
$conn = mysqli_connect($servername, $username, $password, $database);
$sqlfieldsgrabber = "SELECT * FROM catz";
$resultfieldgrabber = $conn->query($sqlfieldsgrabber);

if ($resultfieldgrabber->num_rows > 0) {
    // output data of each row
    while($row = $resultfieldgrabber->fetch_assoc()) {
        
        
        $fieldname = $row["name"];
        $fieldidgrab = $row["id"];
       
echo "<option>". $fieldname . "</option>\n";

      
     }}
      
      ?>

  </select>
</div>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
              <div class="form-group">
                <label for="exampleInputPassword1">Patient</label>
                <input name="patientname" type="text" class="form-control" id="exampleInputPassword1" placeholder="Enter patient name">
              </div>
                
                <div class="form-group">
                <label for="exampleInputPassword1">Age</label>
                <input name="age" type="text" class="form-control" id="exampleInputPassword1" placeholder="Enter patient name">
              </div>
                
                
              <div class="form-group">
                <label for="exampleInputFile"> Thumbnail</label>
                <input name="fileToUpload" type="file" id="fileToUpload">
                <p class="help-block">Case thumbnail</p>
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
					<button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="submit" role="button">Save</button>
				</div>
			</div>
		</div>
        
        </form>
        
        
	</div>
  </div>
</div>
        
        </div>
        
        
        
        
            <!-- line modal shares-->
        <div class="newcasepopup"> 
<div class="modal fade" id="squarespaceModalshares" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
   <!--       CHAT         -->
        

        
        
        
        
        
                                            
  <script type="text/javascript">

      
      $(function(){  // $(document).ready shorthand
  $('.thumbnail').fadeIn('slow');
});
      
      
      
      
      
      
</script> 
      
      
      
 
    
        </div>
    </body>

    
    
    
    
    
    </html>
     
                
