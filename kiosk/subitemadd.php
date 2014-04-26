<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$logspath = "images/dynamic/logos";
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
<?php include './common/file.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
  $str = "SELECT ifnull(max(iditem), 0) as maxid FROM t_item;";
	$maxid = $db->queryCount($str, "maxid");

	if ($maxid == null) {
		$ok = false;
	}
  else {
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
    
    $idgrp = $conssel[0];
    $newid = $maxid + 1;
    $phone = $tel[0].".".$tel[1].".".$tel[2];
	  $str = "INSERT INTO t_item (iditem, idgrp, phone, hall, floor, xpos, ypos, pathimage, tag) VALUES (".$newid.", ".$idgrp.", '".$phone."', '".$hall."', '".$floor."', ".$xpos.", ".$ypos.", '".$pathimage."', '".addslashes($tag)."');";
    $db->autocommit(false);
    if ($db->query($str) == 0) {
		  $ok = false;
	  }
    else {
      $n = count($cons);
      for ($i = 0 ; $i < $n ; $i++) {
	      $str = "INSERT INTO t_itemext (iditem, idattr, idlang, attrval) VALUES (".$newid.", '".$attr[$i]."', ".$idlang[$i].", '".addslashes($cons[$i])."');";
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
  }

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subitemman.php');";
echo "</script>";
?>
