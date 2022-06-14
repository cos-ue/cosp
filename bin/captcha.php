<?php
/**
 * This file contains functions to generate a captcha-code
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * generates a captcha code
 * @param bool $special sets if special chars are used for captcha
 * @param bool $ext true if captcha is requested from a module
 * @return array base64 encoded captcha jpeg
 */
function generateCaptcha($special = true, $ext = false)
{
    if ($special && $ext == false) {
        $special = config::$SPECIAL_CHARS_CAPTCHA;
    }
    $captcha_code = generateRandomString(10, $special);

    $width = 160;
    $height = 38;
    $target_layer = imagecreatetruecolor($width, $height);

    //fills the background colour with black
    $captcha_background = imagecolorallocatealpha($target_layer, 0, 0, 0, 0);
    imagefill($target_layer, 0, 0, $captcha_background);

   //randomly generates a number of lines to be drawn, with a min max
    $lineCount = random_int(4, 6);
    for ($i = 0; $i < $lineCount; $i++) {
        $startX = random_int(0, $width);
        $startY = random_int(0, $height);
        $endX = random_int(0, $width);
        $endY = random_int(0, $height);
        //the random color for each line
        $color = imagecolorallocatealpha($target_layer, random_int(50, 255), random_int(50, 255), random_int(50, 255), random_int(0,50));
        //draws the line with the start coordinates $startX,$startY and the end coordinates $endX,$endY
        //the used color of the drawn line comes from $color 
        imageline($target_layer, $startX, $startY, $endX, $endY, $color);
    }

    //creates the random color for the $captcha_code
    //exclusion of too dark colours to guarantee legibility
    $red=random_int(150,255);
    $blue=random_int(70,255);
    $green=random_int(70,255);

    $captcha_text_color = imagecolorallocate($target_layer, $red, $green, $blue);
    imagestring($target_layer, 5, 45, 11, $captcha_code, $captcha_text_color);

    ob_start();
    imagejpeg($target_layer);
    $image_data = ob_get_contents();
    ob_end_clean();

    $base64 = $base64 = 'data:image/jpeg;base64,' . base64_encode($image_data);
    return array("captcha" => $base64, 'code' => $captcha_code);
}