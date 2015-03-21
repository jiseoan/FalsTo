<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$basepath = "images/dynamic/gourmet";
$idgrp = $_POST["idgrp"];
$attrfile = $_POST["attrfile"];
$idparentitem = $_POST["idparentitem"];
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
      
		  $prefix = $basepath."/";
		  $dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);

		  if (strlen($dstpath) > 0) {
        $pathimage = $dstpath;
			  break;
      }
    }
    
    $newid = $maxid + 1;
	  $str = "INSERT INTO t_item (iditem, idgrp, pathimage, tag, idparentitem, price) VALUES (".$newid.", ".$idgrp.", '".$pathimage."', '".addslashes($tag)."', ".$idparentitem.", '".addslashes($price)."');";
    
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
      fileDelete($pathimage);
    }
  }

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subfoodmenuman.php');";
echo "</script>";
?>
