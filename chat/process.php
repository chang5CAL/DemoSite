<?php
require_once('/includes/servset.php');

require_once('/includes/config.php');
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: ".mysqli_connect_error());
}

$_GET['toid'];
$_GET['tun'];
$_GET['fun'];
$from_user_id = $_SESSION['id'];
$from_username = $_SESSION['username'];


$uploaddir = '/storage/user_files/';
$uploaddirpath = '/storage/user_files/';
$original_filename = $_FILES['file']['name'];

$extensions = explode(".",$original_filename);
$extension = $extensions[count($extensions)-1];
$uniqueName = basename(uniqid().".".$extension);
$uploadfile = $uploaddir . $uniqueName;
$uploadfilepath = $uploaddirpath . $uniqueName;

$file_type = "file";

if ($extension=="jpg" || $extension=="jpeg" || $extension=="gif" || $extension == "png")
{
    $file_type="image";

    $size=filesize($_FILES['file']['tmp_name']);

        $image =$_FILES["file"]["name"];
        $uploadedfile = $_FILES['file']['tmp_name'];

        if ($image)
        {
            if($extension=="jpg" || $extension=="jpeg" )
            {
                $uploadedfile = $_FILES['file']['tmp_name'];
                $src = imagecreatefromjpeg($uploadedfile);
            }
            else if($extension=="png")
            {
                $uploadedfile = $_FILES['file']['tmp_name'];
                $src = imagecreatefrompng($uploadedfile);
            }
            else
            {
                $src = imagecreatefromgif($uploadedfile);
            }

            list($width,$height)=getimagesize($uploadedfile);

            $newwidth=225;
            $newheight=($height/$width)*$newwidth;
            $tmp=imagecreatetruecolor($newwidth,$newheight);

            imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

            $filename = $uploaddir . "small" .$uniqueName;

            imagejpeg($tmp,$filename,100);

            imagedestroy($src);
            imagedestroy($tmp);
        }


}
$result = array("file_name"=>$uniqueName,"file_path"=>$uploadfilepath,"file_type"=>$file_type);


if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

    $from_user_id = $_SESSION['id'];
    $message_content = json_encode($result);
    $to_id = isset($_GET["toid"]) ? $_GET["toid"] : 0;
    $tun = isset($_GET["tun"]) ? $_GET["tun"] : 0;
    //$fun = isset($_GET["fun"]) ? $_GET["fun"] : 0;


    $query = "insert into messages (message_date,from_id,to_id,to_uname,from_uname,message_content,message_type) values ".
        "('".time()."', $from_user_id, $to_id, '".$_GET["tun"]."', '$from_username', '".mysqli_real_escape_string($message_content)."','file')";
    mysqli_query($conn, $query);




    //echo $uploadfilepath;
    $items = '';
    $items .= <<<EOD
{
"chatboxtitle": "{$_GET["tun"]}",
"sender": "{$_SESSION["username"]}",
"filename": "{$filename}",
"path": "{$uploadfilepath}"
},
EOD;

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




?>