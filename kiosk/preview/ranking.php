<?php
$fd = fopen("../json/ranking.json", "r");
if ($fd){
  while (!feof($fd)) {
    $str = fgets($fd);
    echo $str;
  }
  fclose($fd);
}
?>
