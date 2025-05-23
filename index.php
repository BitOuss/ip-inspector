<?php
// Enable logging (set to true to log visits)
$log_enabled = false;

// Get client IP
$ip = $_SERVER['REMOTE_ADDR'];

// Fetch data from IPWHO.IS API
$api_url = "https://ipwho.is/{$ip}";
$response = file_get_contents($api_url);
$data = json_decode($response, true);

// Fallback values
$country = $data['country'] ?? 'Unknown';
$city = $data['city'] ?? '';
$isp = $data['connection']['isp'] ?? 'Unknown ISP';

// Get user agent info
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$browser = get_browser_name($user_agent);
$os = get_os_name($user_agent);
$device = is_mobile($user_agent) ? 'Mobile' : 'Desktop';

// Local time
$local_time = date("H:i");

// Optional: log visit
if ($log_enabled) {
    $log = "[" . date("Y-m-d H:i:s") . "] $ip - $country, $city - $browser - $os\n";
    file_put_contents('logs/visits.txt', $log, FILE_APPEND);
}

// Helper functions
function get_browser_name($user_agent) {
    if (strpos($user_agent, 'Firefox') !== false) return 'Firefox';
    if (strpos($user_agent, 'Chrome') !== false) return 'Chrome';
    if (strpos($user_agent, 'Safari') !== false) return 'Safari';
    if (strpos($user_agent, 'Opera') !== false) return 'Opera';
    if (strpos($user_agent, 'Edge') !== false) return 'Edge';
    return 'Unknown';
}

function get_os_name($user_agent) {
    if (preg_match('/windows nt/i', $user_agent)) return 'Windows';
    if (preg_match('/macintosh|mac os x/i', $user_agent)) return 'macOS';
    if (preg_match('/linux/i', $user_agent)) return 'Linux';
    if (preg_match('/android/i', $user_agent)) return 'Android';
    if (preg_match('/iphone|ipad/i', $user_agent)) return 'iOS';
    return 'Unknown';
}

function is_mobile($user_agent) {
    return preg_match('/android|iphone|ipad|mobile/i', $user_agent);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP Inspector</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            height: 100vh;
        }
        .box {
            background: #1e1e1e;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #00ffae;
            margin-bottom: 20px;
            text-align: center;
        }
        .item {
            margin-bottom: 10px;
        }
        .label {
            color: #888;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>IP Inspector</h1>
        <div class="item"><span class="label">📍 IP:</span> <?php echo $ip; ?></div>
        <div class="item"><span class="label">🌎 Location:</span> <?php echo "$city, $country"; ?></div>
        <div class="item"><span class="label">📡 ISP:</span> <?php echo $isp; ?></div>
        <div class="item"><span class="label">🖥 Browser:</span> <?php echo $browser; ?></div>
        <div class="item"><span class="label">💻 OS:</span> <?php echo $os; ?></div>
        <div class="item"><span class="label">📱 Device:</span> <?php echo $device; ?></div>
        <div class="item"><span class="label">🕓 Local Time:</span> <?php echo $local_time; ?></div>
    </div>
</body>
</html>
