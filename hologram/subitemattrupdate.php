<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$attrname = $_POST["attrname"];
$idlang = $_POST["idlang"];
$idattr = $_POST["idattr"];
$idattrnew = $_POST["idattrnew"];
?>

<?php include './common/db.php'; ?>
<?php
$nnames = count($attrname);
$nidlangs = count($idlang);
$nidattrs = count($idattr);

$ok = $db->open();

if ($ok) {
  for ($i = 0, $k = 0 ; $i < $nidattrs ; $i++) {
    for ($j = 0 ; $j < $nidlangs ; $j++, $k++) {
			$str = "update t_itemattr set idattr = '".$idattrnew[$i]."', name = '".$attrname[$k]."' where idattr = '".$idattr[$i]."' and idlang = ".$idlang[$j].";";
			$n = $db->query($str);
      if ($n <= 0) {
 	      $str = "INSERT INTO t_itemattr (idattr, idlang, name) VALUES ('".$idattrnew[$i]."', ".$idlang[$j].", '".$attrname[$k]."');";
	      $db->query($str);
      }
    }
  }

	$db->close();
}

echo "<script>";
if ($ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
else {
	echo "alert('".$langRes["message"][1][$manLangCode]."');";
}
echo "document.location.replace('./subitemattrman.php');";
echo "</script>";
?>
