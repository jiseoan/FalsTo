<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del2 = $_POST["del"];
?>
<?php include './common/file.php'; ?>
<?php

$ok = true;

for ($i = 0, $n = count($del2) ; $i < $n ; $i++) {
	fileDelete($del2[$i]);
}
?>

<html>
<body>

<form id="upForm" name="upForm" method="post" action="submainslideman.php">
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
