<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

?>
<?php include './common/file.php'; ?>
<?
$logspath = "images/dynamic/logos";
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$iditem = isset($_POST["iditem"]) ? intval($_POST["iditem"]) : 0;
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
<?php

$ok = $db->open();

if ($ok) {
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
  $db->autocommit(false);
	$str = "update t_item set idgrp = ".$idgrp." where iditem = ".$iditem.";";
  if ($db->query($str) < 0) {
		$ok = false;
	}
  else {
    if (strlen($path3ddata) > 0) {
  	  $str = "update t_item set path3ddata = '".$path3ddata."' where iditem = ".$iditem.";";
      $db->query($str);
    }
    if (strlen($pathimage) > 0) {
  	  $str = "update t_item set pathimage = '".$pathimage."' where iditem = ".$iditem.";";
      $db->query($str);
    }
    if (strlen($paththumbnail) > 0) {
  	  $str = "update t_item set paththumbnail = '".$paththumbnail."' where iditem = ".$iditem.";";
      $db->query($str);
    }
    
    $n = count($cons);
    for ($i = 0 ; $i < $n ; $i++) {
	    $str = "update t_itemext set attrval = '".addslashes($cons[$i])."' where iditem = ".$iditem." and idattr = '".$attr[$i]."' and idlang = ".$idlang[$i].";";
      $db->query($str);
      if ($db->query($str) < 0) {
	      $str = "INSERT INTO t_itemext (iditem, idattr, idlang, attrval) VALUES (".$iditem.", '".$attr[$i]."', ".$idlang[$i].", '".addslashes($cons[$i])."');";
        $db->query($str);
	    }
    }

    if ($ok) {
        $n = count($imgdescpath);
        for ($i = 0 ; $i < $n ; $i++) {
			if (strlen($imgdescpath[$i]) <= 0) {
				continue;
			}

			$str = "update t_itemext set attrval = '".$imgdescpath[$i]."' where iditem = ".$iditem." and idattr = '".$attrfile2[$i]."' and idlang = ".$idlang2[$i].";";
			  $db->query($str);
			  if ($db->query($str) < 0) {
				$str = "INSERT INTO t_itemext (iditem, idattr, idlang, attrval) VALUES (".$iditem.", '".$attrfile2[$i]."', ".$idlang2[$i].", '".$imgdescpath[$i]."');";
				$db->query($str);
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
alert('".$langRes["message"][0][$manLangCode]."');
<?php
}
?>
upForm.submit();
</script>
</body>
</html>
