<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del = $_POST["del"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
  for ($i = 0, $n = count($del) ; $i < $n ; $i++) {
    $str = "SELECT ifnull(path3ddata,'') as path3ddata, ifnull(pathimage,'') as pathimage, ifnull(paththumbnail,'') as paththumbnail FROM t_item where iditem = ".$del[$i].";";
		$m = $db->querySelect($str);
		if ($m == 1) {
			$row = $db->goNext();
		  $fname = $row['path3ddata'];
		  if (is_file($fname)) {
			unlink($fname);
		  }
		  $fname = $row['pathimage'];
		  if (is_file($fname)) {
			unlink($fname);
		  }
		  $fname = $row['paththumbnail'];
		  if (is_file($fname)) {
			unlink($fname);
		  }
		}
		
		$db->free();
    
		$str = "delete from t_item where iditem = ".$del[$i].";";
		$db->query($str);
		
		$str = "SELECT attrval FROM t_itemext where iditem = ".$del[$i]." and idattr = 'imgdesc';";
		for ($j = 0, $m = $db->querySelect($str) ; $j < $m ; $j++) {
			$row = $db->goNext();
		  $fname = $row['attrval'];
		  if (is_file($fname)) {
			unlink($fname);
		  }
		}
		
		$db->free();

		$str = "delete from t_itemext where iditem = ".$del[$i].";";
		$db->query($str);
  }

	$db->close();
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subitemman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>
<?php
echo "<script>";
if ($ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
else {
	echo "alert('".$langRes["message"][1][$manLangCode]."');";
}
echo "upForm.submit();";
echo "</script>";
?>
  
</script>
</body>
</html>
