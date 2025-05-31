<?php
function img2html($image, $letters='') {
    /*
      img2html:
        takes an image as an array of data that is created by PHP
        for uploaded files.
    */
    $suffix        = preg_replace('%^[^/]+/%', '', $image['type']);
    $getimagefunc    = "imagecreatefrom$suffix";

    if (!function_exists($getimagefunc)) {
        echo "This server does not support the $getimagefunc() function; exiting";
        return false;
        }

    if (!$letters)
        $letters = 'ARSE';

    $img    = $getimagefunc($image['tmp_name']);
    $count    = 0;

    $numletters        = strlen($letters);
    list($width, $height)    = getimagesize($image['tmp_name']);

    echo '<table cellpadding=0 cellspacing=0 style="cell-spacing: 0px;">';

    for ($y = 0; $y < $height; $y++) {
        echo "<tr>\n";

        for ($x = 0; $x < $width; $x++) {
            $count++;

            $colours    = imagecolorsforindex($img, imagecolorat($img, $x, $y));
            #$hexcolour    = '#' . dechex($colours['red']) . dechex($colours['green']) . dechex($colours['blue']);
            $hexcolour    = sprintf("#%02x%02x%02x", $colours['red'], $colours['green'], $colours['blue']);

            echo    "<td style='height: 3px; width: 3px; padding: 0px; font-size: 3px; background-color: $hexcolour; color: white;'>",
                $letters[($count % $numletters)],
                '</td>';
            }

        #if (!$count % 3)
        #    continue;

        echo "</tr>\n";
        }

    echo "</table>\n";
    }
?>
<html>

<head>

<meta name=author value="pgl@yoyo.org">
<meta name=description value="Convert images to HTML tables">
<meta name=keywords value="img2html, image to HTML, png, jpeg, img, converter, html images, html image">
<meta name=cabal value="">

<? include 'metatags.inc'; ?>

<title>
img2html: convert images to HTML tables
</title>

<?=$style_head?>

</head>

<body>

<p>[ <a href="../">more stuff</a> | <a href="hand-holding-a-beer.png">sample image</a> | <a href="img2html.phps">source</a> ]

<p>This here page converts JPEG and PNG format images into colour HTML tables. It doesn't work too well with big files &ndash; 200 x 200 is about the biggest that works &ndash; <s>and there's generally some bits that don't show up properly for some reason (I suspect it's due to crappiness in the actual image that doesn't show up normally)</s>. But it's fun for 30 seconds or so. The image is also a bit skewed because the table cells aren't perfect squares, which could probably be fixed by fiddling around with ignoring some of the pixels. Excercise. Reader.

<p><b>Update 2006-01-06:</b> Brian Blietz emailed me to kindly let me know about a but in the code:

<blockquote>> You mentioned: "there's generally some bits that don't show up properly
<br>> for some reason (I suspect it's due to crappiness in the actual image
<br>> that doesn't show up normally)"
<br>>
<br>> This is not because of the image, but a bug in the code. You do not take
<br>> into account the values that are less than 0x10 (16 decimal).  The
<br>> values that are below this are only one character in length, not two.
<br>> For example: If  red = 1, green = 1, blue =1, your code prints out #111,
<br>> and it should print #010101
</blockquote>

<p>He also suggested a fix which, is now in use. Thanks Brian!

<p>Here's a <a href="beer-120x118.png">120 x 118 PNG image of a hand holding a beer</a> you can use to try it out.

<p><table cellpadding=3 cellspacing=1>

<form action="<?=$PHP_SELF?>" enctype="multipart/form-data" method=post>

<tr>
<td align=right>image filename: </td>
<td><input type=file size=40 name=image style="width: 250px;"></td>
</tr>

<tr>
<td align=right>letters to use: </td>
<td><input type=text size=40 name=letters value="<?=($letters ? $letters : 'ARSE')?>"></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type=submit value="convert"></td>
</tr>

</form>

</table>

<? if (is_uploaded_file($_FILES['image']['tmp_name'])) { ?>

<pre><?=img2html($_FILES['image'], $letters)?></pre>

<?    } ?>

</body>

</html>