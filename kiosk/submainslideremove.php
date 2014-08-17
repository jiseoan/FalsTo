<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del2 = $_POST["del"];

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
<?php

$ok = true;

for ($i = 0, $n = count($del2) ; $i < $n ; $i++) {
	if (file_exists($del2[$i])) {
		fileDelete($del2[$i]);
	}
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
	echo "alert('삭제하였습니다');";
}
else {
	echo "alert('삭제하지못하였습니다');";
}
echo "upForm.submit();";
echo "</script>";
?>
  
</script>
</body>
</html>
