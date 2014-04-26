<?
function loadLangRes() {
  $fd = fopen("res/manlang.json", "r");
  if (!$fd) {
    echo "error...file reading<br/>";
    return null;
  }

  $str = "";
  while (!feof($fd)) {
    $str .= fgets($fd);
  }

  fclose($fd);

  if (strlen($str) <= 0) {
    echo "empty file<br/>";
    return null;
  }

  return json_decode($str, true);
}

$_SESSION['langRes'] = loadLangRes();
?>