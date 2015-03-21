<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$acttype = $_POST["acttype"];
$pwdnew = $_POST["pwdnew"];
$emailnew = $_POST["emailnew"];
?>

<?php include './common/db.php'; ?>
<?php

$idadmin = "admin";
$str = "";

switch ($acttype) {
  case "pwd":
    $str = "update t_admin set pwd = password('".$pwdnew."') where idadmin = '".$idadmin."';";
    break;
  case "email":
    $str = "update t_admin set email = '".$emailnew."' where idadmin = '".$idadmin."';";
    break;
}

$ok = $db->open();

if ($ok) {
  $db->query($str);
	$db->close();
}
?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok) {
	echo "alert('저장하였습니다');";
}
else {
	echo "alert('저장하지못하였습니다');";
}
echo "document.location.replace('./subadmininfoman.php');";
echo "</script>";
?>
  </body>
</html>
