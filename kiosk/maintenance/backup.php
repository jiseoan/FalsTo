<?php
$compath = '/common/';
if (isset($_SERVER["DOCUMENT_ROOT"]) && strlen($_SERVER["DOCUMENT_ROOT"]) > 0) {
  $compath = '..'.$compath;
}
else {
  $compath = dirname(dirname(__FILE__))."/".$compath;
}

include $compath.'backup.php';
?>
<?php
ini_set('display_errors','off');
$manpage = isset($_GET['manpage']) ? $_GET['manpage'] : '';

$ok = Backup($backup_dir, $web_dir, $db_dir, $db_ip, $db_port, $db_user, $db_pwd, $db_name);

if (strlen($manpage) > 0) {
  echo '<html>';
  echo '<body bgcolor="#f1f1f1">';
  echo '<script>';
  if ($ok == false) {
    echo 'alert("백업하지 못하였습니다");';
  }
  echo 'document.location.replace("../'.$manpage.'.php");';
  echo '</script>';
  echo '</body>';
  echo '</html>';
}
else {
  echo ($ok ? "OK" : "ERR");
}

?>
