<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$del = $_POST["del"];
?>
<?php include './common/env.php'; ?>
<?php include './common/file.php'; ?>
<?php
$ok = false;

if (count($del) > 0) {
	$dbEnv = "-P ".$db_port." -h ".$db_ip." -u".$db_user." -p".$db_pwd;
	$srczip = $backup_dir."/".$del[0]."/src.zip";
	$sql = $backup_dir."/".$del[0]."/".$db_name.".sql";

	$ok = true;

	if (is_file($srczip)) {
		$webdirtmp = $web_dir."2";
		rename($web_dir, $webdirtmp);
		$zip = new ZipArchive;
		if ($zip->open($srczip)) {
			$zip->extractTo($web_dir);
			$zip->close();
			rmdir_rf($webdirtmp);
		}
		else {
			$ok = false;
			rename($webdirtmp, $web_dir);
		}
	}

	if (is_file($sql)) {
		chdir($db_dir);
		$result = passthru("mysqladmin ".$dbEnv." -f drop ".$db_name, $retval);
		$result = passthru("mysql ".$dbEnv." < ".$sql, $retval);
	}
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subrestoreman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>
<?php
echo "<script>";
if (!$ok) {
	echo "alert('복원하지 못하였습니다');";
}
echo "upForm.submit();";
echo "</script>";
?>
  
</script>
</body>
</html>
