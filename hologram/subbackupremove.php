<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del = $_POST["del"];
?>
<?php include './common/env.php'; ?>
<?php include './common/file.php'; ?>
<?php
for ($i = 0, $n = count($del) ; $i < $n ; $i++) {
	rmdir_rf($backup_dir."/".$del[$i]);
}
?>

<html>
<body>
<form id="upForm" name="upForm" method="post" action="subrestoreman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>

<script>
alert('<? echo $langRes["message"][0][$manLangCode] ?>');
upForm.submit();
</script>

</body>
</html>
