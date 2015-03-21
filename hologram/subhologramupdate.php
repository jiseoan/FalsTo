<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idclient = $_POST["idclient"];
$cliname = $_POST["cliname"];
$clipos = $_POST["clipos"];
?>

<?php include './common/db.php'; ?>
<?php
$nidclient = count($idclient);

$ok = $db->open();

if ($ok) {
	for ($i = 0, $k = 0 ; $i < $nidclient ; $i++) {
		$str = "update t_client set name = '".$cliname[$i]."', position = '".$clipos[$i]."' where idclient = ".$idclient[$i].";";
		$db->query($str);
	}

	$db->close();
}
?>

<html>
<body>
<form id="upForm" name="upForm" method="post" action="subhologramman.php">
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
