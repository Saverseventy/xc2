<?php
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");

$USERS = [
    "admin" => ["password" => "123456"],
    "user1" => ["password" => "pass123"],
    "user2" => ["password" => "pass456"]
];
$user=$_GET["username"]??""; $pass=$_GET["password"]??"";
if(!isset($USERS[$user]) || $USERS[$user]["password"]!==$pass){die("Auth failed");}

$stream_url = "https://abslive.akamaized.net/dash/live/2099522/gnews3/manifest.mpd";
$key_id = "d5d848730e4a4f9b962290039dd2b96b";
$key = "c959dc12f1bff5a66d030117fb7e9855";

echo "#EXTM3U\n";
echo "#EXT-X-VERSION:3\n";
echo "#EXTINF:-1,GMA News TV\n";
echo "#EXTVLCOPT:http-origin=https://www.iwanttfc.com\n";
echo "#EXTVLCOPT:http-referrer=https://www.iwanttfc.com/\n";
echo "#EXTVLCOPT:http-user-agent=Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0\n";
echo "#KODIPROP:inputstream.adaptive.manifest_type=mpd\n";
echo "#KODIPROP:inputstream.adaptive.license_type=org.w3.clearkey\n";
echo "#KODIPROP:inputstream.adaptive.license_key=$key_id:$key\n";
echo $stream_url;
