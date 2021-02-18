<?php

//help from: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
session_start();

$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

//get random characters for captcha
function generateString($input, $strength = 5) {
    $inputLength = strlen($input);
    $randomString = "";
    for ($i = 0; $i < $strength; $i++) {
        $randomChar = $input[mt_rand(0, $inputLength - 1)];
        $randomString .= $randomChar;
    }

    return $randomString;
}

// create image in which to put the characters for the captcha
$image = imagecreatetruecolor(200, 50);

imageantialias($image, true);

//region background-colors for the captcha
$colors = [];

$red = rand(125,175);
$green = rand(125,175);
$blue = rand(125,175);

// 5 different colors for the background
for ($i = 0; $i < 5; $i++) {
    $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
}

imagefill($image, 0, 0, $colors[0]);

for ($i = 0; $i < 10; $i++) {
    imagesetthickness($image, rand(2,10));
    $rectColor = $colors[rand(1,4)];
    imagerectangle($image, rand(-10,190), rand(-10,10), rand(-10, 190), rand(40,60), $rectColor);
}
//endregion

//region text
$black = imagecolorallocate($image,0,0,0);
$white = imagecolorallocate($image,255,255,255);
$textColors = [$black, $white];

$fonts = [dirname(__FILE__).'/fonts/acme.ttf', dirname(__FILE__).'/fonts/ubuntu.ttf', dirname(__FILE__).'/fonts/merriweather.ttf', dirname(__FILE__).'/fonts/playfairdisplay.ttf'];

$stringLength = 6;
$captchaString = generateString($chars, $stringLength);

$_SESSION['captcha_text'] = $captchaString;

for ($i = 0; $i < $stringLength; $i++) {
    $letterSpace = 170 / $stringLength;
    $initial = 15;

    imagettftext($image, 20, rand(-15,15), $initial + $i*$letterSpace, rand(20,40), $textColors[rand(0,1)], $fonts[array_rand($fonts)], $captchaString[$i]);
}
//endregion

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);