<?php
/**
 * Download file.
 */
$download_name = urldecode($_GET["filename"]); 
$file_url = $_GET["fileurl"];
$id = $_GET["id"];
$web_root = $_SERVER["DOCUMENT_ROOT"];
$web_address = $_SERVER['HTTP_HOST'];

global $MyRadio;                
if(!isset($MyRadio)){  
    include_once '../inc/defines.php';        
    include_once '../inc/my_radio.php';
    include_once '../inc/utils.php';
    
    $my_key = $_GET['key'];    
    $MyRadio = new my_radio($my_key,WP_MSCRA_LANG_DEF,0);            
    if ($MyRadio->RESPOSTA_MESSAGE !== 'OK' ){
        if ($MyRadio->IS_DEGUG == true){               
            $title = 'Error API MSC';
            $subtitle = $MyRadio->RESPOSTA_MESSAGE;
        }                           
    }
}
$Vars[0]= 'id='.$id;
$MyRadio->QueryGetTable(seccions::PODCAST, sub_seccions::DOWNLOAD, $Vars);

$pos = strrpos($file_url, $web_address);
if($pos){
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }
    $file_url = str_replace ($protocol. $web_address .'/', '', $file_url);
    $file_url = $web_root ."/". $file_url;
    $file_url = str_replace('//', '/', $file_url);
    //die($protocol . " --- " .$web_root . " --- " .$web_address . " --- " . $file_url );
}

$filename = basename ($file_url) ;
$file_extension = strtolower (substr (strrchr ($filename, '.'), 1)) ;


function mscra_getFileSize($url) {
    if (substr($url,0,4)=='http') {
        $x = array_change_key_case(get_headers($url, 1),CASE_LOWER);
        if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; }
        else { $x = $x['content-length']; }
    }
    else { $x = @filesize($url); }
    return $x;
}

$filesize = mscra_getFileSize($file_url);

function mscra_fileExists($path){
    return (@fopen($path,"r")==true);
}

if(!mscra_fileExists($file_url))
    die("<br>The file <b> $file_url </b> doesn\'t exist; check the URL");


//This will set the Content-Type to the appropriate setting for the file
switch ($file_extension)
{
    case 'kmz':
        $content_type = 'application/vnd.google-earth.kmz' ;
        break ;
    case 'kml':
        $content_type = 'application/vnd.google-earth.kml+xml' ;
        break ;
    case 'pdf':
        $content_type = 'application/pdf' ;
        break ;
    case 'exe':
        $content_type = 'application/octet-stream' ;
        break ;
    case 'zip':
        $content_type = 'application/zip' ;
        break ;
    case 'doc':
        $content_type = 'application/msword' ;
        break ;
    case 'xls':
        $content_type = 'application/vnd.ms-excel' ;
        break ;
    case 'ppt':
        $content_type = 'application/vnd.ms-powerpoint' ;
        break ;
    case 'gif':
        $content_type = 'image/gif' ;
        break ;
    case 'png':
        $content_type = 'image/png' ;
        break ;
    case 'jpeg':
    case 'jpg':
        $content_type = 'image/jpg' ;
        break ;
    case 'mp3':
        $content_type = 'audio/mpeg' ;
        break ;
    case 'mp4a':
        $content_type = 'audio/mp4' ;
        break ;
    case 'wav':
        $content_type = 'audio/x-wav' ;
        break ;
    case 'mpeg':
    case 'mpg':
    case 'mpe':
        $content_type = 'video/mpeg' ;
        break ;
    case 'mov':
        $content_type = 'video/quicktime' ;
        break ;
    case 'avi':
        $content_type = 'video/x-msvideo' ;
        break ;

    //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
    case 'php':
    case 'htm':
    case 'html':
    case 'txt':
        die ('<b>Cannot be used for $file_extension files!') ;
        break;
    default:
        $content_type = 'application/force-download' ;
}

//die("<br> - file_extension::  ". $file_extension ."<br> - content_type::  ". $content_type ."<br> - file_name::  ". $file_name ."<br> - file_url::  ". $file_url ."<br> - file size::  ". $filesize . "<br> - curl exist::  ". function_exists('curl_version') ."<br> - allow_url_fopen::  ". ($fp=@fopen($file_url,'rb')) );

header('Pragma: public') ;
header('Expires: 0') ;
header('Cache-Control: must-revalidate, post-check=0, pre-check=0') ;
header('Cache-Control: private') ;
header('Content-Type: ' . $content_type);
header("Content-Description: File Transfer");
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$download_name.'('.$filename.')'."\"");
header('Content-Length: '.$filesize);
header('Connection: close');

if($fp=@fopen($file_url,'rb')){
    sleep(1);
    ignore_user_abort();
    set_time_limit(0);
    while(!feof($fp))
    {
        echo (@fread($fp, 1024*8));
        ob_flush();
        flush();
    }
    fclose ($fp);

}else if(function_exists('curl_version')){
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $file_url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    // display file
    echo $contents;
    curl_close($ch);

}else{
    ob_clean();
    flush();
    @readfile ($file_url) ;
}

clearstatcache();

exit;

?>
