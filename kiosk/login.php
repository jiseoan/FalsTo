<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

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
	}
	else {
		$str = "select count(*) as cnt from t_admin where idadmin = '".$userid."';";
		$n = $db->queryCount($str, "cnt");
		if ($n > 0) {
			$msg = "비밀번호를 확인하세요.";
		}
		else {
			$msg = "아이디를 확인하세요.";
		}
	}

	$db->close();
}
else {
	$msg = "DB에 접속할 수 없습니다.";
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
