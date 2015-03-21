<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del = $_POST["del"];
?>

<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	for ($i = 0, $n = count($del) ; $i < $n ; $i++) {
		$str = "SELECT ifnull(pathimage,'') as pathimage FROM t_item where iditem = ".$del[$i].";";
		$m = $db->querySelect($str);
		if ($m == 1) {
			$row = $db->goNext();
			$fname = $row['pathimage'];
			fileDelete($fname);
		}
		
		$db->free();
    
		$str = "delete from t_item where iditem = ".$del[$i].";";
		$db->query($str);

		$str = "delete from t_itemext where iditem = ".$del[$i].";";
		$db->query($str);
	}

	$db->close();
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subfoodmenuman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>
<?php
echo "<script>";
if ($ok) {
	echo "alert('삭제하였습니다');";
}
else {
	echo "alert('일부 혹은 전부를 삭제하지못하였습니다');";
}
echo "upForm.submit();";
echo "</script>";
?>
  
</script>
</body>
</html>
