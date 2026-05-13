<?php

$width = 1200;
$height = 630;
$image = imagecreatetruecolor($width, $height);
imagealphablending($image, true);
imagesavealpha($image, true);

function color($image, int $r, int $g, int $b, int $a = 0)
{
    return imagecolorallocatealpha($image, $r, $g, $b, $a);
}

$bg = color($image, 5, 7, 18);
$panel = color($image, 15, 21, 40, 28);
$line = color($image, 142, 119, 255, 78);
$purple = color($image, 138, 77, 255);
$violet = color($image, 177, 108, 255);
$gold = color($image, 255, 178, 26);
$gold2 = color($image, 255, 216, 74);
$cyan = color($image, 22, 215, 255);
$green = color($image, 24, 242, 162);
$white = color($image, 242, 245, 255);
$muted = color($image, 166, 176, 205);

imagefill($image, 0, 0, $bg);

for ($y = 0; $y < $height; $y++) {
    $ratio = $y / $height;
    $shade = color($image, (int) (4 + $ratio * 8), (int) (7 + $ratio * 7), (int) (18 + $ratio * 20), 0);
    imageline($image, 0, $y, $width, $y, $shade);
}

for ($x = 0; $x < $width; $x += 34) {
    for ($y = 0; $y < $height; $y += 28) {
        imagesetpixel($image, $x, $y, color($image, 163, 141, 255, 72));
    }
}

imagefilledellipse($image, 230, 150, 460, 260, color($image, 138, 77, 255, 112));
imagefilledellipse($image, 970, 130, 380, 240, color($image, 22, 215, 255, 116));
imagefilledellipse($image, 900, 520, 520, 180, color($image, 255, 178, 26, 118));

imagefilledroundedrectangle($image, 66, 58, 1134, 572, 28, $panel);
imagerectangle($image, 66, 58, 1134, 572, $line);

function imagefilledroundedrectangle($image, int $x1, int $y1, int $x2, int $y2, int $radius, int $color): void
{
    imagefilledrectangle($image, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);
    imagefilledrectangle($image, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
    imagefilledellipse($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
}

$fontRegular = __DIR__ . '/../assets/fonts/Ubuntu-R.ttf';
$fontBold = __DIR__ . '/../assets/fonts/Ubuntu-B.ttf';

function text($image, int $size, int $x, int $y, int $color, string $font, string $copy): void
{
    imagettftext($image, $size, 0, $x, $y, $color, $font, $copy);
}

$logoX = 116;
$logoY = 112;
$cell = 24;
$gap = 6;
$blocks = [
    [0, 0, $gold2], [1, 0, $gold2], [2, 0, $green],
    [0, 1, $gold], [1, 1, $gold], [2, 1, $gold2],
    [0, 2, $gold], [1, 2, $gold], [0, 3, $gold],
];

foreach ($blocks as [$cx, $cy, $blockColor]) {
    imagefilledroundedrectangle(
        $image,
        $logoX + $cx * ($cell + $gap),
        $logoY + $cy * ($cell + $gap),
        $logoX + $cx * ($cell + $gap) + $cell,
        $logoY + $cy * ($cell + $gap) + $cell,
        4,
        $blockColor
    );
}

text($image, 34, 230, 162, $white, $fontBold, 'TRACKERS');
text($image, 34, 458, 162, $gold, $fontBold, 'LENS');
text($image, 19, 232, 217, $green, $fontBold, 'AI-powered • Privacy first • Open ecosystem');

text($image, 42, 116, 318, $white, $fontBold, 'Local AI Data Monitoring');
text($image, 40, 116, 374, $violet, $fontBold, 'APIs, RSS & Realtime Data');
text($image, 22, 120, 445, $muted, $fontRegular, 'Build smart trackers, local dashboards and browser automations.');
text($image, 22, 120, 480, $muted, $fontRegular, 'Privacy-first architecture powered by Trackers Lens.');

imagefilledroundedrectangle($image, 830, 142, 1082, 414, 18, color($image, 8, 13, 28, 18));
imagerectangle($image, 830, 142, 1082, 414, color($image, 177, 108, 255, 58));

for ($i = 0; $i < 4; $i++) {
    imagefilledroundedrectangle($image, 866 + $i * 47, 178, 900 + $i * 47, 326 - $i * 22, 5, [$cyan, $green, $purple, $gold][$i]);
}

imageline($image, 866, 344, 1048, 344, color($image, 166, 176, 205, 82));
imageline($image, 866, 304, 1048, 252, $violet);
imageline($image, 866, 304, 906, 280, $violet);
imageline($image, 906, 280, 950, 294, $violet);
imageline($image, 950, 294, 992, 236, $violet);
imageline($image, 992, 236, 1048, 252, $violet);

text($image, 20, 844, 472, $white, $fontBold, 'Browser runtime');
text($image, 17, 844, 506, $muted, $fontRegular, 'API • WebSocket • RSS • AI Agents');

imagepng($image, __DIR__ . '/../assets/seo/trackers-lens-og.png', 9);
imagedestroy($image);
