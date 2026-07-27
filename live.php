<?php
header("Access-Control-Allow-Origin: *");

$VALID_USER = "admin";
$VALID_PASS = "123456";

$user = $_GET["username"] ?? "";
$pass = $_GET["password"] ?? "";
$id   = (int)($_GET["id"] ?? 0);
$fmt  = $_GET["format"] ?? "ts";

// Check login
if ($user !== $VALID_USER || $pass !== $VALID_PASS || !$id) {
    header("HTTP/1.1 404 Not Found");
    die("#EXTM3U\n# Invalid login or stream ID");
}

// Full channel list mapping: stream_id => REAL URL
$map = [
    1001 => "https://live.nxplay.com.br/TNT/index.m3u8",
    1002 => "https://live.nxplay.com.br/TNT_NOVELAS/index.m3u8",
    1003 => "https://live.nxplay.com.br/TNT_SERIES/index.m3u8",
    1004 => "https://live.nxplay.com.br/TOONCAST/index.m3u8",
    1005 => "https://live.nxplay.com.br/TRACE_SPORT/index.m3u8",
    1006 => "https://live.nxplay.com.br/TV_APARECIDA/index.m3u8",
    1007 => "https://live.nxplay.com.br/TV_ASTRAL/index.m3u8",
    1008 => "https://live.nxplay.com.br/TV_BRASIL/index.m3u8",
    1009 => "https://live.nxplay.com.br/TV_BRASIL_2/index.m3u8",
    1010 => "https://live.nxplay.com.br/TV_CULTURA/index.m3u8",
    1011 => "https://live.nxplay.com.br/UOL/index.m3u8",
    1012 => "https://live.nxplay.com.br/UPCHANNEL/index.m3u8",
    1013 => "https://live.nxplay.com.br/URBAN_DOCS/index.m3u8",
    1014 => "https://live.nxplay.com.br/URBAN_KIDS/index.m3u8",
    1015 => "https://live.nxplay.com.br/URBAN_MOVIES/index.m3u8",
    1016 => "https://live.nxplay.com.br/URBAN_OTAKU/index.m3u8",
    1017 => "https://live.nxplay.com.br/URBAN_RETRO/index.m3u8",
    1018 => "https://live.nxplay.com.br/URBAN_SERIES/index.m3u8",
    1019 => "https://live.nxplay.com.br/URBAN_TRAVEL/index.m3u8",
    1020 => "https://live.nxplay.com.br/VEJA_PLUS/index.m3u8",
    1021 => "https://live.nxplay.com.br/VIVAX/index.m3u8",
    1022 => "https://live.nxplay.com.br/WARNER_CHANNEL/index.m3u8",
    1023 => "https://live.nxplay.com.br/WESTERN_BOUND/index.m3u8",
    1024 => "https://live.nxplay.com.br/WHE_PLAY/index.m3u8",
    1025 => "https://live.nxplay.com.br/XSPORTS/index.m3u8"
];

if (!isset($map[$id])) {
    header("HTTP/1.1 404 Not Found");
    die("# Stream ID $id not found");
}

$realUrl = $map[$id];

// 🎯 Correct: Always send original .m3u8 link (never convert to fake .ts)
if ($fmt === "ts") {
    header("Location: $realUrl", true, 302);
} else {
    header("Location: $realUrl", true, 302);
}
exit;
