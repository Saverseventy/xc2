<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// --------------------------
// LOGIN CREDENTIALS
// --------------------------
$VALID_USER = "admin";
$VALID_PASS = "123456";

$user      = $_GET["username"] ?? "";
$pass      = $_GET["password"] ?? "";
$action    = $_GET["action"] ?? "";
$catFilter = $_GET["category_id"] ?? "";
$vod_id    = $_GET["vod_id"] ?? "";
$series_id = $_GET["series_id"] ?? "";

// --------------------------
// AUTHENTICATION
// --------------------------
if ($user !== $VALID_USER || $pass !== $VALID_PASS) {
    echo json_encode([
        "user_info" => ["auth" => 0, "status" => "Disabled", "message" => "Invalid login"]
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
    "allowed_output_formats"=> ["m3u8", "ts", "mp4", "mkv"]
];

$serverInfo = [
    "url"             => parse_url($base, PHP_URL_HOST),
    "port"            => $proto === "https" ? "443" : "80",
    "https_port"      => "443",
    "server_protocol" => $proto,
    "https"           => ($proto === "https"),
    "xt"              => 1,
    "url_https"       => $base,
    "timezone"        => "America/Sao_Paulo",
    "timestamp_now"   => time(),
    "time_now"        => date("Y-m-d H:i:s")
];

$logo_base = "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Flag_of_Brazil.svg/120px-Flag_of_Brazil.svg.png";

// ==================================================
// 🟢 LIVE TV: CATEGORIES + METADATA ONLY
// stream_id = num + 1000 → PERFECT MATCH with live.php
// ==================================================
$live_categories = [
    ["category_id" => "1", "category_name" => "📺 General",         "parent_id" => 0],
    ["category_id" => "2", "category_name" => "🎬 Movies & Series",  "parent_id" => 0],
    ["category_id" => "3", "category_name" => "👨‍👩‍👧‍👦 Family & Kids", "parent_id" => 0],
    ["category_id" => "4", "category_name" => "⚽ Sports",           "parent_id" => 0],
    ["category_id" => "5", "category_name" => "📰 News & Info",      "parent_id" => 0]
];

$live_raw = [
    ["num"=>1,"name"=>"TNT","cat"=>"2","logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/TNT_Serious_Logo.svg/120px-TNT_Serious_Logo.svg.png"],
    ["num"=>2,"name"=>"TNT NOVELAS","cat"=>"2","logo"=>$logo_base],
    ["num"=>3,"name"=>"TNT SERIES","cat"=>"2","logo"=>$logo_base],
    ["num"=>4,"name"=>"TOONCAST","cat"=>"3","logo"=>$logo_base],
    ["num"=>5,"name"=>"TRACE SPORT","cat"=>"4","logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/e/e5/Trace_Sports_logo.svg/120px-Trace_Sports_logo.svg.png"],
    ["num"=>6,"name"=>"TV APARECIDA","cat"=>"1","logo"=>$logo_base],
    ["num"=>7,"name"=>"TV ASTRAL","cat"=>"1","logo"=>$logo_base],
    ["num"=>8,"name"=>"TV BRASIL","cat"=>"1","logo"=>$logo_base],
    ["num"=>9,"name"=>"TV BRASIL 2","cat"=>"1","logo"=>$logo_base],
    ["num"=>10,"name"=>"TV CULTURA","cat"=>"5","logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/TV_Cultura_logo.svg/120px-TV_Cultura_logo.svg.png"],
    ["num"=>11,"name"=>"UOL","cat"=>"5","logo"=>$logo_base],
    ["num"=>12,"name"=>"UP CHANNEL","cat"=>"1","logo"=>$logo_base],
    ["num"=>13,"name"=>"URBAN DOCS","cat"=>"1","logo"=>$logo_base],
    ["num"=>14,"name"=>"URBAN KIDS","cat"=>"3","logo"=>$logo_base],
    ["num"=>15,"name"=>"URBAN MOVIES","cat"=>"2","logo"=>$logo_base],
    ["num"=>16,"name"=>"URBAN OTAKU","cat"=>"3","logo"=>$logo_base],
    ["num"=>17,"name"=>"URBAN RETRO","cat"=>"2","logo"=>$logo_base],
    ["num"=>18,"name"=>"URBAN SERIES","cat"=>"2","logo"=>$logo_base],
    ["num"=>19,"name"=>"URBAN TRAVEL","cat"=>"1","logo"=>$logo_base],
    ["num"=>20,"name"=>"VEJA PLUS","cat"=>"5","logo"=>$logo_base],
    ["num"=>21,"name"=>"VIVAX","cat"=>"1","logo"=>$logo_base],
    ["num"=>22,"name"=>"WARNER CHANNEL","cat"=>"2","logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Warner_Channel_2019.svg/120px-Warner_Channel_2019.svg.png"],
    ["num"=>23,"name"=>"WESTERN BOUND","cat"=>"2","logo"=>$logo_base],
    ["num"=>24,"name"=>"WHE PLAY","cat"=>"1","logo"=>$logo_base],
    ["num"=>25,"name"=>"X SPORTS","cat"=>"4","logo"=>$logo_base]
];

$live = [];
foreach ($live_raw as $s) {
    $live[] = [
        "num"               => $s["num"],
        "name"              => $s["name"],
        "stream_type"       => "live",
        "stream_id"         => $s["num"] + 1000,
        "stream_icon"       => $s["logo"],
        "category_id"       => $s["cat"],
        "epg_channel_id"    => strtolower(str_replace(" ", "_", $s["name"])),
        "tv_archive"        => 0,
        "tv_archive_duration"=> 0,
        "direct_source"     => ""
    ];
}

// ==================================================
// 🟠 MOVIES: CATEGORIES + FULL METADATA + LOGOS
// vod_id: 2001–2999 → matches movie.php
// ==================================================
$vod_categories = [
    ["category_id"=>"201","category_name"=>"🎬 Action","parent_id"=>0],
    ["category_id"=>"202","category_name"=>"😂 Comedy","parent_id"=>0],
    ["category_id"=>"203","category_name"=>"👻 Horror & Thriller","parent_id"=>0],
    ["category_id"=>"204","category_name"=>"❤️ Romance & Drama","parent_id"=>0],
    ["category_id"=>"205","category_name"=>"🇧🇷 Brazilian Films","parent_id"=>0],
    ["category_id"=>"206","category_name"=>"🎭 Documentaries","parent_id"=>0]
];

$vod_raw = [
    ["id"=>2001,"name"=>"City of God","cat"=>"205",
     "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/9/9d/City_of_God_%28film%29.jpg/220px-City_of_God_%28film%29.jpg",
     "plot"=>"Two boys growing up in a violent neighborhood of Rio de Janeiro — one becomes a photographer, the other a drug dealer.",
     "genre"=>"Crime, Drama", "rating"=>"8.6", "year"=>"2002", "duration"=>"130 mins"],
    ["id"=>2002,"name"=>"The Devil’s Backbone","cat"=>"203",
     "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/e/e0/Devils_backbone.jpg/220px-Devils_backbone.jpg",
     "plot"=>"A ghost story set during the Spanish Civil War at an orphanage holding a secret.",
     "genre"=>"Horror, Drama, Fantasy", "rating"=>"7.4", "year"=>"2001", "duration"=>"106 mins"],
    ["id"=>2003,"name"=>"Brazil Comedy Special","cat"=>"202",
     "logo"=>$logo_base,
     "plot"=>"Collection of stand‑up comedy from top Brazilian comedians.",
     "genre"=>"Comedy, Stand‑up", "rating"=>"6.9", "year"=>"2025", "duration"=>"90 mins"],
    ["id"=>2004,"name"=>"Amazon: The Untold Story","cat"=>"206",
     "logo"=>"https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Cabecas_de_series.jpg/220px-Cabecas_de_series.jpg",
     "plot"=>"Nature documentary exploring the Amazon rainforest and its indigenous people.",
     "genre"=>"Documentary, Nature", "rating"=>"8.1", "year"=>"2024", "duration"=>"60 mins"],
    ["id"=>2005,"name"=>"Elite Squad","cat"=>"205",
     "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/e/e0/Tropa_de_Elite.jpg/220px-Tropa_de_Elite.jpg",
     "plot"=>"Captain Nascimento leads Rio de Janeiro’s BOPE police squad against drug gangs.",
     "genre"=>"Action, Crime, Drama", "rating"=>"8.0", "year"=>"2007", "duration"=>"115 mins"]
];

$vod = [];
foreach ($vod_raw as $m) {
    $vod[] = [
        "vod_id"             => $m["id"],
        "num"                => $m["id"] - 2000 + 1,
        "name"               => $m["name"],
        "stream_icon"        => $m["logo"],
        "category_id"        => $m["cat"],
        "container_extension" => "mp4",
        "direct_source"      => "",
        "info" => [
            "plot"       => $m["plot"],
            "genre"      => $m["genre"],
            "rating"     => $m["rating"],
            "year"       => $m["year"],
            "duration"   => $m["duration"],
            "cast"       => "",
            "director"   => "",
            "video_codec"=> "",
            "audio_codec"=> "",
            "bitrate"    => 0
        ]
    ];
}

// ==================================================
// 🔵 SERIES: CATEGORIES + LOGOS + FULL SEASONS/EPISODES
// series_id:3001–3999 | episode_id:3001001 → matches series.php
// ==================================================
$series_categories = [
    ["category_id"=>"301","category_name"=>"📺 Brazilian Series","parent_id"=>0],
    ["category_id"=>"302","category_name"=>"🌍 International Shows","parent_id"=>0],
    ["category_id"=>"303","category_name"=>"👨‍👩‍👧‍👦 Family Series","parent_id"=>0],
    ["category_id"=>"304","category_name"=>"⚔️ Action & Adventure","parent_id"=>0]
];

$series_raw = [
    [
        "series_id"=>3001, "name"=>"3% – Three Percent", "cat"=>"301",
        "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/c/c7/3_Percent_Netflix.jpg/220px-3_Percent_Netflix.jpg",
        "plot"=>"In a future divided between the affluent 'Inland' and the impoverished 'Offshore', 20‑year‑olds undergo a rigorous selection process where only 3% succeed.",
        "genre"=>"Sci‑Fi, Drama, Thriller", "rating"=>"7.4", "year"=>"2016‑2020",
        "seasons"=>[
            "1"=>[
                ["id"=>3001001,"num"=>1,"title"=>"The Candidate","duration"=>45],
                ["id"=>3001002,"num"=>2,"title"=>"The Map","duration"=>43],
                ["id"=>3001003,"num"=>3,"title"=>"The Knife","duration"=>49],
                ["id"=>3001004,"num"=>4,"title"=>"The Gateway","duration"=>50]
            ],
            "2"=>[
                ["id"=>3001005,"num"=>1,"title"=>"Blood, Sweat & Tears","duration"=>48],
                ["id"=>3001006,"num"=>2,"title"=>"The Way Back","duration"=>46]
            ]
        ]
    ],
    [
        "series_id"=>3002, "name"=>"Invisible City", "cat"=>"301",
        "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/f/f2/Invisible_City_poster.jpg/220px-Invisible_City_poster.jpg",
        "plot"=>"An environmental police officer uncovers a hidden world of mythical entities while investigating his wife’s death in Rio de Janeiro.",
        "genre"=>"Fantasy, Crime, Drama", "rating"=>"6.6", "year"=>"2021‑2023",
        "seasons"=>[
            "1"=>[
                ["id"=>3002001,"num"=>1,"title"=>"The Murder","duration"=>51],
                ["id"=>3002002,"num"=>2,"title"=>"The Giantess","duration"=>47],
                ["id"=>3002003,"num"=>3,"title"=>"The Shapeshifter","duration"=>53]
            ],
            "2"=>[
                ["id"=>3002004,"num"=>1,"title"=>"New Beginnings","duration"=>55]
            ]
        ]
    ],
    [
        "series_id"=>3003, "name"=>"Irmandade (Brotherhood)", "cat"=>"301",
        "logo"=>"https://upload.wikimedia.org/wikipedia/en/thumb/3/31/Irmandade_poster.jpg/220px-Irmandade_poster.jpg",
        "plot"=>"An honest lawyer gets involved with criminals to protect his imprisoned brother.",
        "genre"=>"Crime, Drama, Thriller", "rating"=>"7.0", "year"=>"2019‑2021",
        "seasons"=>[
            "1"=>[
                ["id"=>3003001,"num"=>1,"title"=>"Episode 1","duration"=>42],
                ["id"=>3003002,"num"=>2,"title"=>"Episode 2","duration"=>45]
            ]
        ]
    ]
];

$series_list = [];
foreach ($series_raw as $s) {
    $series_list[] = [
        "series_id"     => $s["series_id"],
        "name"          => $s["name"],
        "category_id"   => $s["cat"],
        "cover"         => $s["logo"],
        "stream_icon"   => $s["logo"],
        "plot"          => $s["plot"],
        "genre"         => $s["genre"],
        "rating"        => $s["rating"],
        "release_date"  => explode("‑", $s["year"])[0],
        "director"      => "",
        "cast"          => ""
    ];
}

// ==================================================
// 🔄 API ROUTING
// ==================================================
switch ($action) {
    // === LIVE TV ===
    case "get_live_categories":
        echo json_encode($live_categories, JSON_PRETTY_PRINT);
        break;
    case "get_live_streams":
        $out = $live;
        if ($catFilter !== '') $out = array_values(array_filter($out, fn($i) => $i["category_id"] === $catFilter));
        echo json_encode($out, JSON_PRETTY_PRINT);
        break;

    // === MOVIES / VOD ===
    case "get_vod_categories":
        echo json_encode($vod_categories, JSON_PRETTY_PRINT);
        break;
    case "get_vod_streams":
        $out = $vod;
        if ($catFilter !== '') $out = array_values(array_filter($out, fn($i) => $i["category_id"] === $catFilter));
        echo json_encode($out, JSON_PRETTY_PRINT);
        break;
    case "get_vod_info":
        $found = array_filter($vod, fn($i) => (string)$i["vod_id"] === (string)$vod_id);
        echo $found ? json_encode(reset($found), JSON_PRETTY_PRINT) : '{}';
        break;

    // === SERIES ===
    case "get_series_categories":
        echo json_encode($series_categories, JSON_PRETTY_PRINT);
        break;
    case "get_series":
        $out = $series_list;
        if ($catFilter !== '') $out = array_values(array_filter($out, fn($i) => $i["category_id"] === $catFilter));
        echo json_encode($out, JSON_PRETTY_PRINT);
        break;
    case "get_series_info":
        $result = [];
        foreach ($series_raw as $sr) {
            if ((string)$sr["series_id"] === (string)$series_id) {
                $eps = [];
                foreach ($sr["seasons"] as $sn => $elist) {
                    $seps = [];
                    foreach ($elist as $e) {
                        $seps[] = [
                            "id"                  => $e["id"],
                            "episode_num"         => $e["num"],
                            "title"               => $e["title"],
                            "container_extension" => "mp4",
                            "info"                => ["duration" => $e["duration"]]
                        ];
                    }
                    $eps[$sn] = $seps;
                }
                $result = [
                    "info" => [
                        "name"        => $sr["name"],
                        "cover"       => $sr["logo"],
                        "stream_icon" => $sr["logo"],
                        "plot"        => $sr["plot"],
                        "genre"       => $sr["genre"],
                        "rating"      => $sr["rating"],
                        "releaseDate" => explode("‑", $sr["year"])[0],
                        "category_id" => $sr["cat"]
                    ],
                    "episodes" => $eps
                ];
            }
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
        break;

    // === ACCOUNT INFO / DEFAULT ===
    case "get_account_info":
    default:
        echo json_encode([
            "user_info"   => $userInfo,
            "server_info" => $serverInfo
        ], JSON_PRETTY_PRINT);
}
