<?php
include_once "PEAR.php";
include_once "http_request/request.php";
include_once "http_request/net/Socket.php";
include_once "http_request/net/URL.php";
include_once "allopass.api.inc.php";

$c = new allopass();

// validate the code in allopass
$url = "http://www.allopass.com/check/index.php4";
$c->__process_request($url,$_POST);

// get the destination and the codes by the user
$destination = trim($c->http_request->headers['location']);

if($destination == NULL) {
    setcookie("AP_AC","KO",time()+2,"/");
    header("Location: ".$_POST['DESTINATION']);
    exit;
}

/**
 * what about parse all the $_POST['CODEn']?
 */ 
// we'll need the codes to check later the access to wp pages
$recall_pattern = "/(.*)RECALL=(.*)/";
preg_match($recall_pattern,$destination,$recall);
// destination file
$destination = ereg_replace("[\?|&]$","",$recall[1]);
// codes inserted by the user
$codes = str_replace("RECALL=","",$recall[2]);

// need to know the content-type of the destination
$c->__process_request($destination);
list($content_type,$devnull) = explode(";",$c->http_request->headers['content-type']);
$file_info = getmType($destination,$content_type);

if($file_info['type'] == "html") {

    // it's a html file, we have to show it
    // no problem if url is shown, the code is validated again in the frame
    // caution! we have to find the way to send the data:
    //      wordpress can use permalinks

    $access_code = base64_encode($codes."|".urlencode($_POST['AUTH']));

    setcookie("AP_AC",$access_code,time()+2,"/");

    header("Location: ".$destination);
    ?>
    
    <!--<html>
    <body style="margin:0; padding:0;">
    <iframe style="border:0" width="100%" height="100%" src="<?echo $destination?>"></iframe>
    </body>
    </html>-->
    <?
} else {
    // its media (image, video, zip, etc)
    // download it to hide the path of the file to the user
    header("Content-type: ".$content_type);
    header('Content-Disposition: attachment; filename="download.'.$file_info['ext'].'"');
    readfile($destination);
    exit;
}

function getmType($filename,$ctype) {

    // check if it's a text content-type
    if(strstr($ctype,"text/html") !== false) {
        $tp = "html";
        $ext = "";
    } else {
        $tp = "dwnl";
        // get base name of the filename provided by user
        // this is only used if it's a download (non text content-type)
        preg_match("/\.(\w+)$/",$filename,$extension);
        $ext = $extension[1]; 
    }
    return Array("type" => $tp, "ext" => $ext);    
    
}

?>
