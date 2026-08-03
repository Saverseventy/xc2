<?php
require_once __DIR__ . '/config.php';

// Simple auth check
$user = $_GET['username'] ?? '';
$pass = $_GET['password'] ?? '';
if ($user !== XTREAM_USER || $pass !== XTREAM_PASS) {
    http_response_code(403);
    exit('Forbidden');
}

$type = $_GET['type'] ?? ''; // live / vod / series
$id   = (int)($_GET['id'] ?? 0);

// Example: Return a sample video URL
// In production, replace with your actual video file/stream URLs
$sampleVideo = 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4';

header('Location: ' . $sampleVideo);
exit;
