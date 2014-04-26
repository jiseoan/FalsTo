<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$infospath = "images/dynamic/infos";
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idshopinfo = isset($_POST["idshopinfo"]) ? intval($_POST["idshopinfo"]) : 0;
$tmpltype = (int)$_POST["tmpltype"];
$yearfrom = $_POST["yearfrom"];
$monthfrom = $_POST["monthfrom"];
$dayfrom = $_POST["dayfrom"];
$hourfrom = $_POST["hourfrom"];
$minutefrom = $_POST["minutefrom"];
$yearto = $_POST["yearto"];
$monthto = $_POST["monthto"];
$dayto = $_POST["dayto"];
$hourto = $_POST["hourto"];
$minuteto = $_POST["minuteto"];
$title = $_POST["title"];
$descinfo = $_POST["descinfo"];
$descimg = isset($_POST["descimg"]) ? $_POST["descimg"] : array("", "", "", "");
$target = isset($_POST["target"]) ? $_POST["target"] : array("", "", "", "");
$period = $_POST["period"];
$location = isset($_POST["location"]) ? $_POST["location"] : array("", "", "", "");
$descetc = isset($_POST["descetc"]) ? $_POST["descetc"] : array("", "", "", "");
$ticker = $_POST["ticker"];
?>

<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
<?php
$maximage = 6;
$imagespertype = array(1, 1, 6);

$ok = $db->open();

if ($ok) {
	// 파일처리
	$n = $imagespertype[$tmpltype] * 4;
	$imgpath = array();
	$oldpathimage = array();
	for ($i = 0 ; $i < $n ; $i++) {
		$oldpathimage[$i] = "";
		if ($_FILES["file"]["error"][$i] > 0) {
			$imgpath[$i] = "";
			continue;
		}
		
	    $str = "select ifnull(path,'') as path from t_shoppinginfoimage where idshopinfo = ".$idshopinfo." and idlang = ".(($i % 4) + 1)." and seq = ".((int)($i / 4) + 1).";";
		$m = $db->querySelect($str);
		if ($m >= 1) {
			$row = $db->goNext();
			$oldpathimage[$i] = $row['path'];
		}
		$db->free();

		$prefix = $infospath."/_";
		$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);

		if (strlen($dstpath) > 0) {
			$imgpath[$i] = $dstpath;
		}
		else {
			$imgpath[$i] = "";
			$oldpathimage[$i] = "";
		}
	}
	
	// 썸네일
	$thumbnail = "";
	$oldthumbnail = "";
	if ($_FILES["thumbnail"]["error"] <= 0) {
	    $str = "select ifnull(thumbnail,'') as thumbnail from t_shoppinginfo where idshopinfo = ".$idshopinfo.";";
		$m = $db->querySelect($str);
		if ($m >= 1) {
			$row = $db->goNext();
			$oldthumbnail = $row['thumbnail'];
		}
		$db->free();

		$prefix = $infospath."/_";
		$dstpath = uploadfileMove($prefix, $_FILES["thumbnail"]["name"], $_FILES["thumbnail"]["tmp_name"]);
		$thumbnail = (strlen($dstpath) > 0) ? $dstpath : "";
	}

	$postbegin = $yearfrom."-".$monthfrom."-".$dayfrom." ".$hourfrom."-".$minutefrom;
	$postend = $yearto."-".$monthto."-".$dayto." ".$hourto."-".$minuteto;

	if (strlen($thumbnail) > 0) {
		$str = "update t_shoppinginfo set postbegin = '".$postbegin."', postend = '".$postend."', thumbnail = '".$thumbnail."' where idshopinfo = ".$idshopinfo.";";
	}
	else {
		$str = "update t_shoppinginfo set postbegin = '".$postbegin."', postend = '".$postend."' where idshopinfo = ".$idshopinfo.";";
	}
	$db->autocommit(false);
	if ($db->query($str) < 0) {
		$ok = false;
	}
	else {
		for ($j = 0 ; $j < 4 ; $j++) {
			$str = "update t_shoppinginfoext set title = '".addslashes($title[$j])."', descinfo = '".addslashes($descinfo[$j])
				."', descimg = '".addslashes($descimg[$j])."', target = '".addslashes($target[$j])."', period = '".addslashes($period[$j])
				."', location = '".addslashes($location[$j])."', descetc = '".addslashes($descetc[$j])
				."', ticker = '".addslashes($ticker[$j])."' where idshopinfo = ".$idshopinfo." and idlang = ".($j + 1).";";
			
			if ($db->query($str) < 0) {
				$ok = false;
				break;
			}
		}

		for ($i = 0, $n = count($imgpath) ; $ok && $i < $n ; $i++) {
			if (strlen($imgpath[$i]) > 0) {
				$str = "update t_shoppinginfoimage set path = '".$imgpath[$i]."' where idshopinfo = ".$idshopinfo." and idlang = ".(($i % 4) + 1)." and seq = ".((int)($i / 4) + 1).";";

				if ($db->query($str) < 0) {
					$ok = false;
					break;
				}
			}
		}
	}
    
	if ($ok) {
		$db->commit();
		for ($i = 0, $n = count($oldpathimage) ; $i < $n ; $i++) {
			$fname = $oldpathimage[$i];
			if (is_file($fname)) {
				unlink($fname);
			}
			else {
				$fname = iconv("utf-8", "euc-kr", $fname);
				if (is_file($fname)) {
					unlink($fname);
				}
			}

			$fname = $oldthumbnail;
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
		for ($i = 0, $n = count($imgpath) ; $i < $n ; $i++) {
			$fname = $imgpath[$i];
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

		$fname = $thumbnail;
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
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;" bgcolor="#f1f1f1">
<form id="upForm" name="upForm" method="post" action="subshoppinginfoman.php">
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
