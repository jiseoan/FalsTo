<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?
$basepath = "images/dynamic/gourmet";
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$iditem = isset($_POST["iditem"]) ? intval($_POST["iditem"]) : 0;
$idparentitem = $_POST["idparentitem"];
$attrfile = $_POST["attrfile"];
$cons = $_POST["cons"];
$attr = $_POST["attr"];
$idlang = $_POST["idlang"];
$tag = $_POST["tag"];
$price = $_POST["price"];
?>

<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
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

		$prefix = $basepath."/";
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

	$db->autocommit(false);

	$str = "update t_item set idparentitem = ".$idparentitem.", price = '".addslashes($price)."', tag = '".addslashes($tag)."'";
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
			fileDelete($oldpathimage);
		}
	}
	else {
		$db->rollback();
		fileDelete($pathimage);
	}

	$db->close();
}
?>

<html>
<body bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subfoodmenuman.php">
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
