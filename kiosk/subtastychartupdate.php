<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$year = $_POST["year"];
$month = $_POST["month"];
$idlang = $_POST["idlang"];
$orgpath = $_POST["orgpath"];
?>

<?php include './common/file.php'; ?>
<?php include './common/db.php'; ?>
<?php
$baseimgpath = "images/dynamic/gourmet/";

$ok = $db->open();

if ($ok) {
	for ($i = 0, $n = count($orgpath) ; $i < $n ; $i++)
	{
		// 파일 처리
		if ($_FILES["file"]["error"][$i] <= 0) {
			if (strlen($orgpath[$i]) > 0) {
				fileDelete($orgpath[$i]);
			}

			$dstpath = uploadfileMove($baseimgpath, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);
			if (strlen($dstpath) > 0) {
				$str = "update t_tastychart set pathimage = '".$dstpath."' where year = ".$year[$i]." and month = ".$month[$i]." and idlang = ".$idlang[$i].";";
				$db->query($str);
			}
			else {
				$ok = false;
			}
		}
	}

	$db->close();
}

?>
<html>
<body>

<form id="upForm" name="upForm" method="post" action="./subtastychartman.php">
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
