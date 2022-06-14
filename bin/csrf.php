<?php
/**
 * this file contains functions to mitigate csrf attacks
 */

/**
 * creates a CSRF token to check if request is legitimate
 * @return string client hmac string of csrf token
 */
function createCSRFtokenClient() {
    $token = $_SESSION['csrf'];
    $clientToken = generateStringHmac($token);
    return $clientToken;
}

/**
 * checks if a csrf token is good
 * @param string $ClientToken token from client
 * @return false result of check
 */
function checkCSRFtoken($ClientToken) {
    $token = $_SESSION['csrf'];
    $checkToken = generateStringHmac($token);
    dump($token, 8);
    return hash_equals($checkToken, $ClientToken);
}