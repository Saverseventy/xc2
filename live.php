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


// ==============================================
// 🟢 COMPLETE LIVE CHANNEL LIST — ONLY HERE NOW
// stream_id = num + 1000 → PERFECT SYNC
// ==============================================
$live_map = [
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
    1025 => "https://live.nxplay.com.br/XSPORTS/index.m3u8",
    1026 => "https://abslive.akamaized.net/dash/live/2099522/gnews3/manifest.mpd|user-agent=Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0&referrer=https://www.iwanttfc.com/&origin=https://www.iwanttfc.com#manifest_type=mpd&drm_scheme=cenc&drm_key_id=d5d848730e4a4f9b962290039dd2b96b&drm_key=c959dc12f1bff5a66d030117fb7e9855"
];

if (!isset($live_map[$id])) {
    header("HTTP/1.1 404 Not Found");
    die("# Stream ID $id not found");
}

// Redirect to real stream URL
header("Location: " . $live_map[$id], true, 302);
exit;
