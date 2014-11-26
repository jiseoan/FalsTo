<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$basepath = "images/dynamic/gourmet/";
$idlang = $_POST["idlang"];
$attrfile = $_POST["attrfile"];
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
    // 파일처리
		$prefix = $basepath;
    $newid = $maxid + 1;
    
    $n = count($attrfile);
    for ($i = 0 ; $i < $n ; $i++) {
		  if ($_FILES["file"]["error"][$i] <= 0) {
		    $dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);
		  }
      else {
        $dstpath = '';
      }
      
      if (strcmp($attrfile[$i], "paththumb") == 0) {
	      $str = "INSERT INTO t_onlygalleria (idonlygalleria, seqno, idlang, paththumb, pathtextimg, pathimg) VALUES (".$newid.", ".$newid.", ".$idlang[$i].", '".$dstpath."', '', '');";
      }
      else {
	      $str = "UPDATE t_onlygalleria SET ".$attrfile[$i]." = '".$dstpath."' where idonlygalleria = ".$newid." and idlang = ".$idlang[$i].";";
      }
      
      $db->query($str);
    }
  }

	$db->close();
}


echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subonlygalleriaman.php');";
echo "</script>";
?>
