<?php
/**
 * make it possible to get the real name of a browser
 * @param string $user_agent user agent as string
 * @return string clear name of browser
 */
function getBrowserName($user_agent)
{
    $t = strtolower($user_agent);
    $t = " " . $t;

    // Humans / Regular Users
    if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'Opera';
    elseif (strpos($t, 'edge')) return 'Edge';
    elseif (strpos($t, 'chrome')) return 'Chrome';
    elseif (strpos($t, 'safari')) return 'Safari';
    elseif (strpos($t, 'firefox')) return 'Firefox';
    elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'Internet Explorer';

    // Search Engines
    elseif (strpos($t, 'google')) return '[Bot] Googlebot';
    elseif (strpos($t, 'bing')) return '[Bot] Bingbot';
    elseif (strpos($t, 'slurp')) return '[Bot] Yahoo! Slurp';
    elseif (strpos($t, 'duckduckgo')) return '[Bot] DuckDuckBot';
    elseif (strpos($t, 'baidu')) return '[Bot] Baidu';
    elseif (strpos($t, 'yandex')) return '[Bot] Yandex';
    elseif (strpos($t, 'sogou')) return '[Bot] Sogou';
    elseif (strpos($t, 'exabot')) return '[Bot] Exabot';
    elseif (strpos($t, 'msn')) return '[Bot] MSN';

    // Tools and Bots
    elseif (strpos($t, 'mj12bot')) return '[Bot] Majestic';
    elseif (strpos($t, 'ahrefs')) return '[Bot] Ahrefs';
    elseif (strpos($t, 'semrush')) return '[Bot] SEMRush';
    elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) return '[Bot] Moz or OpenSiteExplorer';
    elseif (strpos($t, 'frog') || strpos($t, 'screaming')) return '[Bot] Screaming Frog';

    // Misc
    elseif (strpos($t, 'facebook')) return '[Bot] Facebook';
    elseif (strpos($t, 'pinterest')) return '[Bot] Pinterest';

    // Bot user agents
    elseif (strpos($t, 'crawler') || strpos($t, 'api') ||
        strpos($t, 'spider') || strpos($t, 'http') ||
        strpos($t, 'bot') || strpos($t, 'archive') ||
        strpos($t, 'info') || strpos($t, 'data')) return '[Bot] Other';

    return 'Other (Unknown)';
}

/**
 * This will search and find which browser the user claims to have
 * @return array structured result
 */
function detectBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (preg_match_all($pattern, $u_agent, $matches)) {
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent, //all infos from user_agent
        'name' => $bname,  //name of browser
        'version' => $version, //Number
        'platform' => $platform, //operating system
        'hrname' => getBrowserName($u_agent)
    );
}