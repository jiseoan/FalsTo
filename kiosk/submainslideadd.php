<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
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
<?
?>
<?php
$baseimgpath .= "/";
$ok = ($_FILES["file"]["error"] == 0);

if ($ok) {
	$dstpath = uploadfileMove($baseimgpath, $_FILES["file"]["name"], $_FILES["file"]["tmp_name"]);
	$ok = (strlen($dstpath) > 0);
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
	echo "저장하지못하였습니다');";
}
echo "upForm.submit();";
echo "</script>";
?>
  </body>
</html>
