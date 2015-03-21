<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idrelimg = $_POST["idrelimg"];
$riname = $_POST["riname"];
$yearto = $_POST["yearto"];
$monthto = $_POST["monthto"];
$dayto = $_POST["dayto"];
$hourto = $_POST["hourto"];
$minuteto = $_POST["minuteto"];
$returnpath = $_POST["returnpath"];
?>

<?php include './common/db.php'; ?>
<?php
$nidrelimg = count($idrelimg);

$ok = $db->open();

if ($ok) {
  for ($i = 0, $k = 0 ; $i < $nidrelimg ; $i++) {
		$str = "update t_release set name = '".$riname[$i]."', applydate = '".$yearto[$i]."-".$monthto[$i]."-".$dayto[$i]." ".$hourto[$i].":".$minuteto[$i].":00' where idrelease = ".$idrelimg[$i].";";
		$db->query($str);
  }

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
