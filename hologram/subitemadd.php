<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$attrfile = $_POST["attrfile"];
$attrfile2 = $_POST["attrfile2"];
$conssel = $_POST["conssel"];
$attrsel = $_POST["attrsel"];
$idlangsel = $_POST["idlangsel"];
$cons = $_POST["cons"];
$attr = $_POST["attr"];
$idlang = $_POST["idlang"];
$idlang2 = $_POST["idlang2"];
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
    $path3ddata = "";
    $pathimage = "";
    $paththumbnail = "";
    
    $n = count($attrfile);
    for ($i = 0 ; $i < $n ; $i++) {
		  if ($_FILES["file"]["error"][$i] > 0) {
			  continue;
		  }
      
      if (strcmp($attrfile[$i], "path3ddata") == 0) {
  		  $dstpath = "upmodel/_".$_FILES["file"]["name"][$i];
        $path3ddata = $dstpath;
      }
      else if (strcmp($attrfile[$i], "pathimage") == 0) {
  		  $dstpath = "upimg/_".$_FILES["file"]["name"][$i];
        $pathimage = $dstpath;
      }
      else if (strcmp($attrfile[$i], "paththumbnail") == 0) {
  		  $dstpath = "upimg/_".$_FILES["file"]["name"][$i];
        $paththumbnail = $dstpath;
      }
      else {
  		  continue;
      }
      
 		  if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $dstpath)) {
			  continue;
		  }
		  
      if (strcmp($attrfile[$i], "path3ddata") == 0) {
        $path3ddata = "";
      }
      else if (strcmp($attrfile[$i], "pathimage") == 0) {
        $pathimage = "";
      }
      else if (strcmp($attrfile[$i], "paththumbnail") == 0) {
        $paththumbnail = "";
      }
    }
    
    $imgdescpath = array();
    $n = count($attrfile2);
    for ($i = 0 ; $i < $n ; $i++) {
		  if ($_FILES["file2"]["error"][$i] > 0) {
        $imgdescpath[$i] = '';
			  continue;
		  }
      
      $imgdescpath[$i] = "upimg/_".$_FILES["file2"]["name"][$i];
 		  if (move_uploaded_file($_FILES["file2"]["tmp_name"][$i], $imgdescpath[$i])) {
			  continue;
		  }
		  
      $imgdescpath[$i] = '';
    }
    
    $idgrp = $conssel[0];
    $newid = $maxid + 1;
	  $str = "INSERT INTO t_item (iditem, idgrp, path3ddata, pathimage, paththumbnail) VALUES (".$newid.", ".$idgrp.", '".$path3ddata."', '".$pathimage."', '".$paththumbnail."');";
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
      
      if ($ok) {
        $n = count($imgdescpath);
        for ($i = 0 ; $i < $n ; $i++) {
	        $str = "INSERT INTO t_itemext (iditem, idattr, idlang, attrval) VALUES (".$newid.", '".$attrfile2[$i]."', ".$idlang2[$i].", '".$imgdescpath[$i]."');";
          if ($db->query($str) == 0) {
		        $ok = false;
            break;
	        }
        }
      }
    }
    
    if ($ok) {
      $db->commit();
    }
    else {
      $db->rollback();
      if (strlen($path3ddata) > 0) {
        unlink($path3ddata);
      }
      if (strlen($pathimage) > 0) {
        unlink($pathimage);
      }
      if (strlen($paththumbnail) > 0) {
        unlink($paththumbnail);
      }
      
      $n = count($imgdescpath);
      for ($i = 0 ; $i < $n ; $i++) {
        if (strlen($imgdescpath[$i]) > 0) {
          unlink($imgdescpath[$i]);
        }
      }
    }
  }

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
echo "document.location.replace('./subitemman.php');";
echo "</script>";
?>
