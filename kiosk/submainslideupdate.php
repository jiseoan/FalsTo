<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$orgpath = $_POST["orgpath"];
$seqno = $_POST["seqno"];

$slidername = isset($_GET["name"]) ? $_GET["name"] : (isset($_POST["name"]) ? $_POST["name"] : "");

$baseimgpath = "";
$tablename = "";

switch ($slidername) {
	case "gourmet494":
		$baseimgpath = "./images/dynamic/gourmet/mainslide";
		$tablename = "t_gourmet494slide";
		break;
	default:
		$baseimgpath = "./images/dynamic/mainslide";
		$tablename = "t_mainslide";
		break;
}
?>
<?php include './common/file.php'; ?>
<?php include './common/db.php'; ?>
<?php
$n = count($orgpath);
$ok = $db->open();

if ($ok) {
	for ($i = 0 ; $i < $n ; $i++)
	{
		// 파일 처리
		if ($_FILES["file"]["error"][$i] <= 0) {
			fileDelete($orgpath[$i]);
			$dstpath = iconv("utf-8", "euc-kr", $orgpath[$i]);
			if (!move_uploaded_file($_FILES["file"]["tmp_name"][$i], $dstpath)) {
				$ok = false;
			}
		}

		// 순번처리
		$fname = substr(strrchr($orgpath[$i], '/'), 1);
		$str = "update ".$tablename." set seqno = ".$seqno[$i]." where name = '".$fname."';";
		$db->query($str);
	}

	$db->close();
}
?>

<html>
<body>
<form id="upForm" name="upForm" method="post" action="submainslideman.php?name=<? echo $slidername ?>">
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
