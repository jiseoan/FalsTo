<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$del = $_POST["del"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
  for ($i = 0, $n = count($del) ; $i < $n ; $i++) {
		$str = "delete from t_itemattr where idattr = '".$del[$i]."';";
		$db->query($str);
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
