<?php
$path = "downloads";
$files = array_diff(scandir($path), array('.', '..'));


$scan = scandir('downloads');

foreach($scan as $file)
{
    if (!is_dir("downloads/$file"))
    {
        $fl = getimagesize("downloads/$file");
        echo $fl . " - w" . $fl[0] . " h" . $fl[1] . "</br>";
        
        if($fl[0] < 800 && $fl[1] < 800){
            unlink("downloads/$file");
        }
    }
}
