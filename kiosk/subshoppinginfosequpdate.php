<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idshopinfo = $_POST["idshopinfo"];
$seqno = $_POST["seqno"];
?>
<?php include './common/db.php'; ?>
<?php
$n = count($idshopinfo);
$ok = $db->open();

if ($ok) {
	for ($i = 0 ; $i < $n ; $i++)
	{
		$str = "update t_shoppinginfo set seqno = ".$seqno[$i]." where idshopinfo = ".$idshopinfo[$i].";";
		$db->query($str);
	}

	$db->close();
}
?>

<html>
<body>
<form id="upForm" name="upForm" method="post" action="subshoppinginfoman.php">
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
