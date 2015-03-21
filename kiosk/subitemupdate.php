<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php include './common/file.php'; ?>
<?
$logspath = "images/dynamic/logos";
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$iditem = isset($_POST["iditem"]) ? intval($_POST["iditem"]) : 0;
$attrfile = $_POST["attrfile"];
$conssel = $_POST["conssel"];
$attrsel = $_POST["attrsel"];
$idlangsel = $_POST["idlangsel"];
$cons = $_POST["cons"];
$attr = $_POST["attr"];
$idlang = $_POST["idlang"];
$hall = $_POST["hall"][0];
$floor = $_POST["floor"][0];
$xpos = $_POST["xpos"][0];
$ypos = $_POST["ypos"][0];
$tel = $_POST["tel"];
$tag = $_POST["tag"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	// 파일처리
	$pathimage = "";
    
	$n = count($attrfile);
	for ($i = 0 ; $i < $n ; $i++) {
		if ($_FILES["file"]["error"][$i] > 0 || strcmp($attrfile[$i], "pathimage") != 0) {
			continue;
		}

		$prefix = $logspath."/_";
		$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);

		if (strlen($dstpath) > 0) {
			$pathimage = $dstpath;
			break;
		}
	}
	
	$oldpathimage = "";
	if (strlen($pathimage) > 0) {
		$str = "SELECT ifnull(pathimage,'') as pathimage FROM t_item where iditem = ".$iditem.";";
		$m = $db->querySelect($str);
		if ($m >= 1) {
			$row = $db->goNext();
			$oldpathimage = $row['pathimage'];
		}
		$db->free();
	}

	$idgrp = $conssel[0];
	$db->autocommit(false);

    $phone = $tel[0].".".$tel[1].".".$tel[2];
	$str = "update t_item set idgrp = ".$idgrp.", phone = '".$phone."', hall = '".$hall."', floor = '".$floor."', xpos = ".$xpos.", ypos = ".$ypos.", tag = '".addslashes($tag)."'";
	if (strlen($pathimage) > 0) {
		$str .= (", pathimage = '".$pathimage."'");
	}

	$str .= (" where iditem = ".$iditem.";");

	if ($db->query($str) < 0) {
		$ok = false;
	}
	else {
		$n = count($cons);
		for ($i = 0 ; $i < $n ; $i++) {
			$str = "update t_itemext set attrval = '".addslashes($cons[$i])."' where iditem = ".$iditem." and idattr = '".$attr[$i]."' and idlang = ".$idlang[$i].";";
			$db->query($str);
			if ($db->query($str) < 0) {
				$str = "INSERT INTO t_itemext (iditem, idattr, idlang, attrval) VALUES (".$iditem.", '".$attr[$i]."', ".$idlang[$i].", '".addslashes($cons[$i])."');";
				$db->query($str);
			}
		}
	}
    
	if ($ok) {
		$db->commit();
		if (strlen($pathimage) > 0) {
			$fname = $oldpathimage;
			if (is_file($fname)) {
				unlink($fname);
			}
			else {
				$fname = iconv("utf-8", "euc-kr", $fname);
				if (is_file($fname)) {
					unlink($fname);
				}
			}
		}
	}
	else {
		$db->rollback();
		$fname = $pathimage;
		if (is_file($fname)) {
			unlink($fname);
		}
		else {
			$fname = iconv("utf-8", "euc-kr", $fname);
			if (is_file($fname)) {
				unlink($fname);
			}
		}
	}

	$db->close();
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subitemman.php">
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
