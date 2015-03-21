<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php include './common/file.php'; ?>
<?
$dirprfx = "./images/";
if (!isset($_GET["path"]) || !is_dir($dirprfx.$_GET["path"])) {
	echo "error -> path<br/>";
	exit;
}

$arrCtx = array("path"=>"");
$envfrfx = $_GET["path"];

foreach ($arrCtx as $k=>$v) {
	if (array_key_exists($k, $_GET)) {
		$arrCtx[$k] = $_GET[$k];
	}
	else if (array_key_exists($k, $_POST)) {
		$arrCtx[$k] = $_POST[$k];
	}
	else {
		$k2 = $envfrfx.$k;
		if (array_key_exists($k2, $_SESSION)) {
			$arrCtx[$k] = $_SESSION[$k2];
		}
		continue;
	}
}

$namegetext = "?path=".$arrCtx['path'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$baseimgpath = "./images/".$arrCtx['path']."/";
?>
<?php
$ok = ($_FILES["file"]["error"] == 0);

if ($ok) {
	$dstpath = uploadfileMove($baseimgpath, $_FILES["file"]["name"], $_FILES["file"]["tmp_name"]);
	$ok = (strlen($dstpath) > 0);
}

?>

<html>
<body>

<form id="upForm" name="upForm" method="post" action="subimagesman.php<? echo $namegetext; ?>">
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
