<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?
$basepath = "images/dynamic/gourmet/";
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idonlygalleria = $_POST["idonlygalleria"];
$seqno = $_POST["seqno"];
$idlang = $_POST["idlang"];
$attrfile = $_POST["attrfile"];
$orgfile = $_POST["orgfile"];
?>

<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	$str = "SELECT ifnull(max(idonlygalleria), 0) as maxid FROM t_onlygalleria;";
	$maxid = $db->queryCount($str, "maxid");

	if ($maxid == null) {
		$ok = false;
	}
	else {
		$str = "UPDATE t_onlygalleria SET seqno = ".$seqno." where idonlygalleria = ".$idonlygalleria.";";
		$db->query($str);

		// 파일처리
		$prefix = $basepath;
		$newid = $maxid + 1;
    
		$n = count($attrfile);
		for ($i = 0 ; $i < $n ; $i++) {
			if ($_FILES["file"]["error"][$i] <= 0) {
				$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);
				if (strlen($dstpath) > 0) {
					$str = "UPDATE t_onlygalleria SET ".$attrfile[$i]." = '".$dstpath."' where idonlygalleria = ".$idonlygalleria." and idlang = ".$idlang[$i].";";
					if ($db->query($str) > 0 && strlen($orgfile[$i]) > 0) {
						fileDelete($orgfile[$i]);
					}
				}
			}
		}
	}

	$db->close();
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subonlygalleriaman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>

<script language="javascript">
<?php
if ($ok == false) {
?>
	alert('수정하지못하였습니다');
<?php
}
?>
upForm.submit();
</script>
</body>
</html>
