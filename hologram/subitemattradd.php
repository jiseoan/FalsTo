<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
 	$str = "INSERT INTO t_itemattr (idattr, idlang, name) VALUES ('itemnewattr', 1, '".$langRes["label"][0][$manLangCode]."');";
	if ($db->query($str) == 0) {
		$ok = false;
	}

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
echo "document.location.replace('./subitemattrman.php');";
echo "</script>";
?>
