<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

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
	echo "alert('저장하였습니다');";
}
else {
	echo "alert('일부 혹은 전부를 저장하지못하였습니다');";
}
echo "upForm.submit();";
echo "</script>";
?>
</body>
</html>
