<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];
$userid = $_POST["userid"];
$password = $_POST["password"];
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();
$msg = "";

if ($ok) {
    $str = "select count(*) as cnt from t_admin where idadmin = '".$userid."' and pwd = password('".$password."');";
	$n = $db->queryCount($str, "cnt");
	if ($n > 0) {
		$_SESSION['userid'] = $userid;

		$str = "select a.idlang, codelang from t_admin as a left join t_lang as l on (a.idlang = l.idlang) where idadmin = '".$userid."';";
		if ($db->querySelect($str) > 0) {
			$row = $db->goNext();
			$_SESSION['manLang'] = $row['idlang'];
			$_SESSION['manLangCode'] = $row['codelang'];
		}
		$db->free();
	}
	else {
		$str = "select count(*) as cnt from t_admin where idadmin = '".$userid."';";
		$n = $db->queryCount($str, "cnt");
		if ($n > 0) {
			$msg = $langRes["message"][0][$manLangCode];
		}
		else {
			$msg = $langRes["message"][1][$manLangCode];
		}
	}

	$db->close();
}
else {
	$msg = $langRes["message"][2][$manLangCode];
}
?>

<html>
<body bgcolor="#f1f1f1">
<script>
<?
if (strlen($msg) > 0) {
?>
	alert('<? echo $msg; ?>');
	document.location.replace('./index.php');
<?
}
else {
?>
	document.location.replace('./mainman.php');
<?
}
?>
</script>
</body>
</html>
