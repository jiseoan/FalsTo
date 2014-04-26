<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

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
	echo "alert('삭제하였습니다');";
}
else {
	echo "alert('일부 혹은 전부를 삭제하지못하였습니다');";
}
echo "document.location.replace('./subitemattrman.php');";
echo "</script>";
?>
