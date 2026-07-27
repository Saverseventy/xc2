<?php
header("Access-Control-Allow-Origin: *");

// ✅ SAME USER LIST — COPY THIS BLOCK TO movie.php & series.php TOO!
$USERS = [
    "admin"  => ["password" => "123456"],
    "user1"  => ["password" => "pass123"],
    "user2"  => ["password" => "pass456"]
];

$user = $_GET["username"] ?? "";
$pass = $_GET["password"] ?? "";
$id   = (int)($_GET["id"] ?? 0);
$fmt  = $_GET["format"] ?? "ts";

if (!isset($USERS[$user]) || $USERS[$user]["password"] !== $pass || !$id) {
    header("HTTP/1.1 404 Not Found");
    die("#EXTM3U\n# Invalid login or stream ID");
}
$map=[
    // 3% – Three Percent
    3001001=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3001002=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3001003=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3001004=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3001005=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3001006=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    // Invisible City
    3002001=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3002002=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3002003=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3002004=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    // Brotherhood
    3003001=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4",
    3003002=>"https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4"
];

if(!isset($map[$id])){header("HTTP/1.1 404 Not Found");exit("# Episode $id not found");}
header("Location: ".$map[$id],true,302);exit;
