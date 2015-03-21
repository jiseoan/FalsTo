<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$idoperating = $_POST["idoperating"];
$holi = isset($_POST["holi"]) ? $_POST["holi"] : null;
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	$str = "update t_operating set holimon = 'N', holitue = 'N', holiwed = 'N', holithr = 'N', holifri = 'N', holisat = 'N', holisun = 'N' where idoperating = ".$idoperating.";";
	$db->query($str);
	
	for ($i = 0, $n = count($holi) ; $i < $n ; $i++) {
		$str = "update t_operating set ".$holi[$i]." = 'Y' where idoperating = ".$idoperating.";";
		$db->query($str);
	}

	$db->close();
}

?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok) {
	echo "alert('저장하였습니다');";
}
else {
	echo "alert('저장하지못하였습니다');";
}
echo "document.location.replace('./suboperatingdayofweekman.php');";
echo "</script>";
?>
</body>
</html>
