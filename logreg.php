<?php 

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
session_start();
require_once('includes/apiserv.php'); 
require_once('includes/servset.php'); 
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
	die("Database connection failed: ".mysqli_connect_error());
}

// CAPTCHA confirmation
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => [
    'secret' => '6LfdfSQTAAAAAGkcverGjrsSI6ZkcxheOJFIDjfD',
    'response' => $_POST['g-recaptcha-response'],
  ],
]);

$response = json_decode(curl_exec($curl));

if (!$response->success) {
    header("Location: index.php");
}
//Email information
$email = "contact@sapphire-servers.com";
$admin_email = $_POST['email'];
$subject = "Kaseify";
$comment = "Thank you for signing up with Kaseify !";

if (isset($_POST['login'])) {

	$email = $_POST['email'];
	$pass = $_POST['password'];

	$phash = sha1(sha1($pass."salt")."salt");

	$sql = "SELECT * FROM users WHERE email='$email' AND password='$phash';";

	$result = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($result);


	if ($count == 1)
	{


    $row = $result->fetch_assoc();
    $username = $row['username'];
    $query = "SELECT * FROM userdata WHERE username='$username'";

    $query_result = mysqli_query($conn, $query);
    $info = mysqli_fetch_array($query_result);
    $user_id = $info['id'];
    $username = $info['username'];

    $res = mysqli_query($conn, "UPDATE userdata SET online=1, last_active_timestamp = NOW() WHERE username = '$username'");

    $cookie_value = $email;
    setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60), "/");
    $_SESSION['id'] = $user_id;
    $_SESSION['username'] = $username;   
          header('Content-type: application/text');
    echo $_SESSION['username'];
    header("Location: personal.php");
    exit;

	}
	else
	{
		echo "Username or password is incorrect!";
	}
} else if (isset($_POST['register'])) {

	$user = $_POST['username'];
	$pass = $_POST['password'];
  $email = $_POST['email'];
  $dir = "User_". $_POST['username'];

	$phash = sha1(sha1($pass."salt")."salt");

	$sql = "INSERT INTO users (id, username, password ,email,folder,puzplan) VALUES ('', '$user', '$phash' ,'$email', '$user','1')";

	$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    // get the user we just created 
  $sql = "SELECT * FROM users WHERE username='$user' LIMIT 1";
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  // create the new messaging db
  $info = mysqli_fetch_array($result);
  $id = $info['id'];
  $sql = "INSERT INTO userdata SET username='$user', password='', email='$email', name='$user', joined=NOW(), country='', about='', sex='', dob='', skype='', facebook='', twitter='', googleplus='', instagram='', picname='', last_active_timestamp=NOW(), users_id='$id'";
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  //send email

  mail($admin_email, "$subject", $comment, "From: contact@baabelweb.com");    
      
  //////////// FTP Function //////////

  // set the various variables
  $ftproot = "/";
  $srcroot = "/";        
  $srcrela = "iwm/";

  // connect to the destination FTP & enter appropriate directories both locally and remotely
  $ftpc = ftp_connect("ftp.box.com");
  $ftpr = ftp_login($ftpc,"brad@kaseify.com","Kaseify11!");

  if ((!$ftpc) || (!$ftpr)) { echo "FTP connection not established!"; die(); }
  if (!chdir($srcroot)) { echo "Could not enter local source root directory."; die(); }
  if (!ftp_chdir($ftpc,$ftproot)) { echo "Could not enter FTP root directory."; die(); }

  if (ftp_mkdir($ftpc, $dir)) {
   echo "successfully created $dir\n";
  } else {
   echo "There was a problem while creating $dir\n";
  }

  // close the connection
  ftp_close($conn_id);
  header("Location: success.php");
}

?>