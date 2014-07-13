<?php
$fd = fopen("../json/video.json", "r");
if ($fd){
  while (!feof($fd)) {
    $str = fgets($fd);
    echo $str;
  }
  fclose($fd);
}
?>
