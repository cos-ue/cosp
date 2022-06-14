<?php
/**
 * This file contains all functions needed to send emails
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * sends mail from local server
 * @param string $receiver email-adress of receiver
 * @param string $title subject of mail
 * @param string $msg content of mail
 * @param boolean $html_flag selects of mail should be send as html-mail, defaults to false
 * @param string $reply reply adress of mail
 * @param boolean $additionalParam enables additional parameters from config
 */
function sendMail($receiver, $title, $msg, $html_flag = false, $reply = "", $additionalParam = false)
{
    if ($reply == "") {
        $reply = config::$SENDER_ADDRESS;
    }
    $title = "=?UTF-8?Q?" . quoted_printable_encode($title) . "?=";
    $headerAR = array(
        "From" => config::$SENDER_ADDRESS,
        "Reply-To" => $reply,
        "X-Mailer" => "PHP/" . phpversion()
    );
    if ($html_flag) {
        $headerAR["MIME-Version"] = "1.0";
        $headerAR["Content-type"] = "text/html; charset=UTF-8";
    }
    if ($additionalParam) {
        $headerAR = array_merge($headerAR, config::$ADDITIONAL_MAIL_PARAM);
    }
    dump($receiver, 5);
    dump($title, 5);
    dump($msg, 5);
    dump($headerAR, 5);
    dump(mail($receiver, $title, $msg, $headerAR), 5);
    return;
}