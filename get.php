<?php
header("Content-Type: application/vnd.apple.mpegurl; charset=UTF-8");
$user = $_GET["username"] ?? "";
$pass = $_GET["password"] ?? "";
if ($user !== "admin" || $pass !== "123456") {
    echo "#EXTM3U\n# Login Invalid — check username/password";
    exit;
}

// Group mapping
$group_map = [
    "1" => "📺 General",
    "2" => "🎬 Movies & Series",
    "3" => "👨‍👩‍👧‍👦 Family & Kids",
    "4" => "⚽ Sports",
    "5" => "📰 News & Info"
];

$ch = [
    ["TNT",              "2", "https://live.nxplay.com.br/TNT/index.m3u8",                      "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/TNT_Serious_Logo.svg/120px-TNT_Serious_Logo.svg.png"],
    ["TNT NOVELAS",      "2", "https://live.nxplay.com.br/TNT_NOVELAS/index.m3u8",               "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TNT SERIES",       "2", "https://live.nxplay.com.br/TNT_SERIES/index.m3u8",                "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TOONCAST",         "3", "https://live.nxplay.com.br/TOONCAST/index.m3u8",                 "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TRACE SPORT",      "4", "https://live.nxplay.com.br/TRACE_SPORT/index.m3u8",              "https://upload.wikimedia.org/wikipedia/en/thumb/e/e5/Trace_Sports_logo.svg/120px-Trace_Sports_logo.svg.png"],
    ["TV APARECIDA",     "1", "https://live.nxplay.com.br/TV_APARECIDA/index.m3u8",             "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TV ASTRAL",        "1", "https://live.nxplay.com.br/TV_ASTRAL/index.m3u8",                "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TV BRASIL",        "1", "https://live.nxplay.com.br/TV_BRASIL/index.m3u8",                "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TV BRASIL 2",      "1", "https://live.nxplay.com.br/TV_BRASIL_2/index.m3u8",             "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["TV CULTURA",       "5", "https://live.nxplay.com.br/TV_CULTURA/index.m3u8",              "https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/TV_Cultura_logo.svg/120px-TV_Cultura_logo.svg.png"],
    ["UOL",             "5", "https://live.nxplay.com.br/UOL/index.m3u8",                     "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["UP CHANNEL",       "1", "https://live.nxplay.com.br/UPCHANNEL/index.m3u8",               "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN DOCS",       "1", "https://live.nxplay.com.br/URBAN_DOCS/index.m3u8",             "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN KIDS",       "3", "https://live.nxplay.com.br/URBAN_KIDS/index.m3u8",             "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN MOVIES",     "2", "https://live.nxplay.com.br/URBAN_MOVIES/index.m3u8",           "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN OTAKU",      "3", "https://live.nxplay.com.br/URBAN_OTAKU/index.m3u8",            "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN RETRO",      "2", "https://live.nxplay.com.br/URBAN_RETRO/index.m3u8",            "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN SERIES",     "2", "https://live.nxplay.com.br/URBAN_SERIES/index.m3u8",           "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["URBAN TRAVEL",     "1", "https://live.nxplay.com.br/URBAN_TRAVEL/index.m3u8",           "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["VEJA PLUS",        "5", "https://live.nxplay.com.br/VEJA_PLUS/index.m3u8",               "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["VIVAX",           "1", "https://live.nxplay.com.br/VIVAX/index.m3u8",                  "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["WARNER CHANNEL",   "2", "https://live.nxplay.com.br/WARNER_CHANNEL/index.m3u8",         "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Warner_Channel_2019.svg/120px-Warner_Channel_2019.svg.png"],
    ["WESTERN BOUND",    "2", "https://live.nxplay.com.br/WESTERN_BOUND/index.m3u8",          "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["WHE PLAY",         "1", "https://live.nxplay.com.br/WHE_PLAY/index.m3u8",               "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"],
    ["X SPORTS",         "4", "https://live.nxplay.com.br/XSPORTS/index.m3u8",                 "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png"]
];

echo "#EXTM3U\n";
foreach ($ch as $item) {
    [$name, $cat_id, $url, $logo] = $item;
    $group = $group_map[$cat_id] ?? "Other";
    $id = strtolower(str_replace(" ","_",$name));
    echo "#EXTINF:-1 tvg-id=\"$id\" tvg-name=\"$name\" tvg-logo=\"$logo\" group-title=\"$group\",$name\n$url\n";
}
