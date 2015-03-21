<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
 	$str = "INSERT INTO t_itemattr (idattr, idlang, name) VALUES ('itemnewattr', 1, '새아이템속성');";
	if ($db->query($str) == 0) {
		$ok = false;
	}

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subitemattrman.php');";
echo "</script>";
?>
