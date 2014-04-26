<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$acttype = $_POST["acttype"];
$pwdnew = $_POST["pwdnew"];
$emailnew = $_POST["emailnew"];
$manlang = $_POST["manlang"];
?>

<?php include './common/db.php'; ?>
<?php

$idadmin = "admin";
$langChanged = false;

$ok = $db->open();
if ($ok) {
	switch ($acttype) {
	  case "pwd":
		$str = "update t_admin set pwd = password('".$pwdnew."') where idadmin = '".$idadmin."';";
		$db->query($str);
		break;
	  case "email":
		$str = "update t_admin set email = '".$emailnew."' where idadmin = '".$idadmin."';";
		$db->query($str);
		break;
	  case "manlang":
		$str = "update t_admin set idlang = '".$manlang."' where idadmin = '".$idadmin."';";
		$db->query($str);

		$str = "select a.idlang, l.codelang from t_admin as a left join t_lang as l on (a.idlang = l.idlang) where idadmin = '".$idadmin."';";
		if ($db->querySelect($str) > 0) {
			$row = $db->goNext();
			$_SESSION['manLang'] = $row['idlang'];
			$_SESSION['manLangCode'] = $row['codelang'];
			$langChanged = true;
		}
		$db->free();
		break;
	}
	$db->close();
}
?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
else {
	echo "alert('".$langRes["message"][1][$manLangCode]."');";
}
if ($langChanged) {
	echo "parent.document.location.reload();";
}
else {
	echo "document.location.replace('./subadmininfoman.php');";
}
echo "</script>";
?>
  </body>
</html>
