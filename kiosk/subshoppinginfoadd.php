<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$infospath = "images/dynamic/infos";
$tmpltype = (int)$_POST["tmpltype"];
$site = (int)$_POST["site"];
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
$location = $_POST["location"];
$descetc = $_POST["descetc"];
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
  for ($i = 0 ; $i < $n ; $i++) {
		if ($_FILES["file"]["error"][$i] > 0) {
      $imgpath[$i] = "";
			continue;
		}

		$prefix = $infospath."/_";
		$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);
    $imgpath[$i] = (strlen($dstpath) > 0) ? $dstpath : "";
  }
  
  // 썸네일
  $thumbnail = "";
	if ($_FILES["thumbnail"]["error"] <= 0) {
		$prefix = $infospath."/_";
		$dstpath = uploadfileMove($prefix, $_FILES["thumbnail"]["name"], $_FILES["thumbnail"]["tmp_name"]);
    $thumbnail = (strlen($dstpath) > 0) ? $dstpath : "";
  }
  
  $postbegin = $yearfrom."-".$monthfrom."-".$dayfrom." ".$hourfrom."-".$minutefrom;
  $postend = $yearto."-".$monthto."-".$dayto." ".$hourto."-".$minuteto;
	$str = "INSERT INTO t_shoppinginfo (tmpltype, site, postbegin, postend, thumbnail) VALUES (".$tmpltype.", ".$site.", '".$postbegin."', '".$postend."', '".$thumbnail."');";
  $db->autocommit(false);
  if ($db->query($str) == 0) {
		$ok = false;
	}
  else {
    $str = "SELECT LAST_INSERT_ID() as idshopinfo;";
    $idshopinfo = $db->queryCount($str, "idshopinfo");
    
    for ($j = 0 ; $j < 4 ; $j++) {
	    $str = "INSERT INTO t_shoppinginfoext (idshopinfo, idlang, title, descinfo, descimg, target, period, location, descetc, ticker) VALUES (".$idshopinfo.", ".($j + 1).", '".addslashes($title[$j])."', '".addslashes($descinfo[$j])."', '".addslashes($descimg[$j])."', '".addslashes($target[$j])."', '".addslashes($period[$j])."', '".addslashes($location[$j])."', '".addslashes($descetc[$j])."', '".addslashes($ticker[$j])."');";
      
      if ($db->query($str) == 0) {
		    $ok = false;
        break;
	    }
    }
    
    for ($i = 0, $n = count($imgpath) ; $ok && $i < $n ; $i++) {
	    $str = "INSERT INTO t_shoppinginfoimage (idshopinfo, idlang, seq, path) VALUES (".$idshopinfo.", ".(($i % 4) + 1).", ".((int)($i / 4) + 1).", '".$imgpath[$i]."');";
      if ($db->query($str) == 0) {
		    $ok = false;
        break;
	    }
    }
  }
  
  if ($ok) {
    $db->commit();
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


echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subshoppinginfoman.php');";
echo "</script>";
?>
