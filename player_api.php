<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// --------------------------
// YOUR LOGIN DETAILS
// --------------------------
$VALID_USER = "admin";
$VALID_PASS = "123456";

$user   = $_GET["username"] ?? "";
$pass   = $_GET["password"] ?? "";
$action = $_GET["action"] ?? "";
$catFilter = $_GET["category_id"] ?? "";

// --------------------------
// AUTH CHECK
// --------------------------
if ($user !== $VALID_USER || $pass !== $VALID_PASS) {
    echo json_encode([
        "user_info" => [
            "auth"     => 0,
            "status"   => "Disabled",
            "message"  => "Invalid username or password"
        ]
    ], JSON_PRETTY_PRINT);
    exit;
}

$proto = isset($_SERVER["HTTPS"]) ? "https" : "http";
$host  = $_SERVER["HTTP_HOST"];
$base  = "$proto://$host";

$userInfo = [
    "username"              => $user,
    "password"              => $pass,
    "auth"                  => 1,
    "status"                => "Active",
    "exp_date"              => "1999999999",
    "is_trial"              => "0",
    "active_cons"           => 0,
    "created_at"            => time(),
    "max_connections"       => 1,
    "allowed_output_formats"=> ["m3u8", "ts"]
];

$serverInfo = [
    "url"           => parse_url($base, PHP_URL_HOST),
    "port"          => $proto === "https" ? "443" : "80",
    "https_port"    => "443",
    "server_protocol"=> $proto,
    "https"         => ($proto === "https"),
    "xt"            => 1,
    "url_https"     => $base,
    "timezone"      => "America/Sao_Paulo",
    "timestamp_now" => time(),
    "time_now"      => date("Y-m-d H:i:s")
];

// --------------------------
// CATEGORIES
// --------------------------
$categories = [
    ["category_id" => "1", "category_name" => "📺 General",         "parent_id" => 0],
    ["category_id" => "2", "category_name" => "🎬 Movies & Series",  "parent_id" => 0],
    ["category_id" => "3", "category_name" => "👨‍👩‍👧‍👦 Family & Kids", "parent_id" => 0],
    ["category_id" => "4", "category_name" => "⚽ Sports",           "parent_id" => 0],
    ["category_id" => "5", "category_name" => "📰 News & Info",      "parent_id" => 0]
];

// --------------------------
// CHANNELS — stream_id = num+1000 → matches /live.php EXACTLY
// --------------------------
$logo_base = "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png";

$rawStreams = [
    ["num"=>1,  "name"=>"TNT",             "category_id"=>"2", "url"=>"https://live.nxplay.com.br/TNT/index.m3u8",             "logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/TNT_Serious_Logo.svg/120px-TNT_Serious_Logo.svg.png"],
    ["num"=>2,  "name"=>"TNT NOVELAS",     "category_id"=>"2", "url"=>"https://live.nxplay.com.br/TNT_NOVELAS/index.m3u8",      "logo"=>$logo_base],
    ["num"=>3,  "name"=>"TNT SERIES",      "category_id"=>"2", "url"=>"https://live.nxplay.com.br/TNT_SERIES/index.m3u8",       "logo"=>$logo_base],
    ["num"=>4,  "name"=>"TOONCAST",        "category_id"=>"3", "url"=>"https://live.nxplay.com.br/TOONCAST/index.m3u8",        "logo"=>$logo_base],
    ["num"=>5,  "name"=>"TRACE SPORT",     "category_id"=>"4", "url"=>"https://live.nxplay.com.br/TRACE_SPORT/index.m3u8",     "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/e/e5/Trace_Sports_logo.svg/120px-Trace_Sports_logo.svg.png"],
    ["num"=>6,  "name"=>"TV APARECIDA",    "category_id"=>"1", "url"=>"https://live.nxplay.com.br/TV_APARECIDA/index.m3u8",    "logo"=>$logo_base],
    ["num"=>7,  "name"=>"TV ASTRAL",       "category_id"=>"1", "url"=>"https://live.nxplay.com.br/TV_ASTRAL/index.m3u8",       "logo"=>$logo_base],
    ["num"=>8,  "name"=>"TV BRASIL",       "category_id"=>"1", "url"=>"https://live.nxplay.com.br/TV_BRASIL/index.m3u8",       "logo"=>$logo_base],
    ["num"=>9,  "name"=>"TV BRASIL 2",     "category_id"=>"1", "url"=>"https://live.nxplay.com.br/TV_BRASIL_2/index.m3u8",    "logo"=>$logo_base],
    ["num"=>10, "name"=>"TV CULTURA",      "category_id"=>"5", "url"=>"https://live.nxplay.com.br/TV_CULTURA/index.m3u8",     "logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/TV_Cultura_logo.svg/120px-TV_Cultura_logo.svg.png"],
    ["num"=>11, "name"=>"UOL",             "category_id"=>"5", "url"=>"https://live.nxplay.com.br/UOL/index.m3u8",            "logo"=>$logo_base],
    ["num"=>12, "name"=>"UP CHANNEL",      "category_id"=>"1", "url"=>"https://live.nxplay.com.br/UPCHANNEL/index.m3u8",      "logo"=>$logo_base],
    ["num"=>13, "name"=>"URBAN DOCS",      "category_id"=>"1", "url"=>"https://live.nxplay.com.br/URBAN_DOCS/index.m3u8",    "logo"=>$logo_base],
    ["num"=>14, "name"=>"URBAN KIDS",      "category_id"=>"3", "url"=>"https://live.nxplay.com.br/URBAN_KIDS/index.m3u8",    "logo"=>$logo_base],
    ["num"=>15, "name"=>"URBAN MOVIES",    "category_id"=>"2", "url"=>"https://live.nxplay.com.br/URBAN_MOVIES/index.m3u8",  "logo"=>$logo_base],
    ["num"=>16, "name"=>"URBAN OTAKU",     "category_id"=>"3", "url"=>"https://live.nxplay.com.br/URBAN_OTAKU/index.m3u8",   "logo"=>$logo_base],
    ["num"=>17, "name"=>"URBAN RETRO",     "category_id"=>"2", "url"=>"https://live.nxplay.com.br/URBAN_RETRO/index.m3u8",   "logo"=>$logo_base],
    ["num"=>18, "name"=>"URBAN SERIES",    "category_id"=>"2", "url"=>"https://live.nxplay.com.br/URBAN_SERIES/index.m3u8",  "logo"=>$logo_base],
    ["num"=>19, "name"=>"URBAN TRAVEL",    "category_id"=>"1", "url"=>"https://live.nxplay.com.br/URBAN_TRAVEL/index.m3u8",  "logo"=>$logo_base],
    ["num"=>20, "name"=>"VEJA PLUS",       "category_id"=>"5", "url"=>"https://live.nxplay.com.br/VEJA_PLUS/index.m3u8",      "logo"=>$logo_base],
    ["num"=>21, "name"=>"VIVAX",           "category_id"=>"1", "url"=>"https://live.nxplay.com.br/VIVAX/index.m3u8",           "logo"=>$logo_base],
    ["num"=>22, "name"=>"WARNER CHANNEL",  "category_id"=>"2", "url"=>"https://live.nxplay.com.br/WARNER_CHANNEL/index.m3u8", "logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Warner_Channel_2019.svg/120px-Warner_Channel_2019.svg.png"],
    ["num"=>23, "name"=>"WESTERN BOUND",   "category_id"=>"2", "url"=>"https://live.nxplay.com.br/WESTERN_BOUND/index.m3u8",  "logo"=>$logo_base],
    ["num"=>24, "name"=>"WHE PLAY",        "category_id"=>"1", "url"=>"https://live.nxplay.com.br/WHE_PLAY/index.m3u8",       "logo"=>$logo_base],
    ["num"=>25, "name"=>"X SPORTS",        "category_id"=>"4", "url"=>"https://live.nxplay.com.br/XSPORTS/index.m3u8",        "logo"=>$logo_base]
];

// Build OFFICIAL Xtream stream objects
$streams = [];
foreach ($rawStreams as $s) {
    $streams[] = [
        "num"               => $s["num"],
        "name"              => $s["name"],
        "stream_type"       => "live",
        "stream_id"         => $s["num"] + 1000,  // num=1 → 1001 ✅ PERFECT MATCH
        "stream_icon"       => $s["logo"],
        "category_id"       => $s["category_id"],
        "epg_channel_id"    => strtolower(str_replace(" ", "_", $s["name"])),
        "url"               => $s["url"],
        "tv_archive"        => 0,
        "tv_archive_duration"=> 0,
        "direct_source"     => ""
    ];
}

// --------------------------
// FILTER by category_id if requested
// --------------------------
if ($catFilter !== "") {
    $streams = array_filter($streams, function($s) use ($catFilter) {
        return (string)$s["category_id"] === (string)$catFilter;
    });
    $streams = array_values($streams); // re‑index
}

// --------------------------
// ROUTING / ACTIONS
// --------------------------
switch ($action) {
    case "get_live_categories":
        echo json_encode($categories, JSON_PRETTY_PRINT);
        break;

    case "get_live_streams":
        echo json_encode($streams, JSON_PRETTY_PRINT);
        break;

    case "get_vod_categories":
    case "get_series_categories":
        echo json_encode([], JSON_PRETTY_PRINT);
        break;

    case "get_vod_streams":
    case "get_series":
        echo json_encode([], JSON_PRETTY_PRINT);
        break;

    case "get_account_info":
    default:
        echo json_encode([
            "user_info"   => $userInfo,
            "server_info" => $serverInfo
        ], JSON_PRETTY_PRINT);
}
