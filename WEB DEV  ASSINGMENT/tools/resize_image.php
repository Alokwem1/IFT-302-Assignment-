<?php
$src = __DIR__ . '/../static/images/profile.jpg';
$dst = $src; // overwrite
$size = 400; // target square
if (!file_exists($src)) {
    echo "Source file not found: $src\n";
    exit(1);
}
$data = file_get_contents($src);
if ($data === false) { echo "Failed to read source file\n"; exit(1); }
$srcImg = @imagecreatefromstring($data);
if (!$srcImg) { echo "Failed to create image from source (unsupported format?)\n"; exit(1); }
$w = imagesx($srcImg);
$h = imagesy($srcImg);
// calculate square crop from center
if ($w > $h) {
    $src_x = intval(($w - $h) / 2);
    $src_y = 0;
    $src_w = $h;
    $src_h = $h;
} else {
    $src_x = 0;
    $src_y = intval(($h - $w) / 2);
    $src_w = $w;
    $src_h = $w;
}
$dstImg = imagecreatetruecolor($size, $size);
// preserve transparency for png
imagefill($dstImg, 0, 0, imagecolorallocate($dstImg, 255, 255, 255));
imagecopyresampled($dstImg, $srcImg, 0, 0, $src_x, $src_y, $size, $size, $src_w, $src_h);
// save as optimized jpeg with quality 85
$ok = imagejpeg($dstImg, $dst, 85);
if ($ok) {
    echo "Resized and optimized image saved to: $dst\n";
    $origSize = strlen($data);
    $newSize = filesize($dst);
    echo "Original bytes: $origSize -> New bytes: $newSize\n";
    imagedestroy($srcImg);
    imagedestroy($dstImg);
    exit(0);
} else {
    echo "Failed to save resized image\n";
    exit(1);
}
?>