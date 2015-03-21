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
$idrelimg = $del[0];

$ok = $db->open();

if ($ok) {
	date_default_timezone_set('Asia/Seoul');
	$applydate = date("Y-m-d H:i:s");
	$str = "update t_release set applydate = '".$applydate."' where idrelease = ".$idrelimg.";";
	$db->query($str);
	$db->close();
}

?>
<html>
  <body bgcolor="#f1f1f1">
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
</body>
</html>
