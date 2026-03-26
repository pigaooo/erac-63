<?php
$src = 'public/images/logo-2647.png';
if (!file_exists($src)) {
    die('source missing');
}
$img = imagecreatefrompng($src);
$w = imagesx($img);
$h = imagesy($img);
foreach (array(32, 16) as $size) {
    $tmp = imagecreatetruecolor($size, $size);
    imagesavealpha($tmp, true);
    $trans = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
    imagefill($tmp, 0, 0, $trans);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $size, $size, $w, $h);
    $out = "public/favicon-{$size}x{$size}.png";
    imagepng($tmp, $out);
    imagedestroy($tmp);
}
imagedestroy($img);
$pngdata = file_get_contents('public/favicon-32x32.png');
$icoPath = 'public/favicon.ico';
$f = fopen($icoPath, 'wb');
fwrite($f, pack('vvv', 0, 1, 1));
fwrite($f, pack('CCCCvvVV', 32, 32, 0, 0, 1, 32, strlen($pngdata), 22));
fwrite($f, $pngdata);
fclose($f);
echo 'ok';
?>