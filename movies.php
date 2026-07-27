<?php
header("Access-Control-Allow-Origin: *");
$VALID_USER="admin"; $VALID_PASS="123456";
$user=$_GET["username"]??""; $pass=$_GET["password"]??"";
$id=(int)($_GET["id"]??0);

if($user!==$VALID_USER||$pass!==$VALID_PASS||!$id){header("HTTP/1.1 404 Not Found");exit("# Not Found");}

$map=[
    2001=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    2002=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    2003=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    2004=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    2005=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4"
];

if(!isset($map[$id])){header("HTTP/1.1 404 Not Found");exit("# Movie $id not found");}
header("Location: ".$map[$id],true,302);exit;
