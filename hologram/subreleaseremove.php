<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del = $_POST["del"];
$returnpath = $_POST["returnpath"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
  for ($i = 0, $n = count($del) ; $i < $n ; $i++) {
    $str = "SELECT pathimage FROM t_release where idrelease = ".$del[$i].";";
		$m = $db->querySelect($str);
		if ($m == 1) {
			$row = $db->goNext();
		  $fname = $row['pathimage'];
		  if (is_file($fname)) {
			  unlink($fname);
		  }
		}
		
		$db->free();

		$str = "delete from t_release where idrelease = ".$del[$i].";";
		$db->query($str);
  }

	$db->close();
}
?>

<html>
<body>
<form id="upForm" name="upForm" method="post" action="<? echo $returnpath; ?>">
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
