<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

if (isset($_session)) {
	session_destroy();
}

session_start();
?>
<?php include './common/manlang.php'; ?>
<?php include './common/db.php'; ?>
<?
$title = $_SESSION['langRes']["title"];
$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];

$ok = $db->open();
if ($ok) {
	$str = "select a.idlang, codelang from t_admin as a left join t_lang as l on (a.idlang = l.idlang) where idadmin = 'admin';";
	if ($db->querySelect($str) > 0) {
		$row = $db->goNext();
		$_SESSION['manLang'] = $row['idlang'];
		$_SESSION['manLangCode'] = $row['codelang'];
	}
	$db->free();
	$db->close();
}

if (!isset($_SESSION['manLangCode'])) {
	session_destroy();
	echo "DB or Environment Error!<br/>";
	exit();
}

$manLangCode = $_SESSION['manLangCode'];
?>

<html>
<head>
<title><? echo $title[$manLangCode]; ?></title>
<link rel="stylesheet" type="text/css" href="./css/mainpage.css" />
<style type="text/css">
.label-item {
	width:90;
	height: 32px;
	font-family: 돋움;
	font-size: 13px;
	text-align:right;
	font-weight:bold;
}
.label-desc {
	width:350px;
	font-family: 돋움;
	font-size: 11px; color:#666666;
}
.input-text {
	width: 250px;
	height: 32px;
	border-style:solid;
	border-width: 1px;
	border-color: #bfbfbf;
	font-family: 돋움;
	font-size: 13px;
	padding-left: 10px;
}
</style>
<script type="text/javascript">

    function onLogin() {
    	
    	if (loginForm.userid.value.length < 3) {
    		alert("<? echo $langRes["message"][0][$manLangCode] ?>.");
    		loginForm.userid.focus();
    		return false;
    	}
    	
    	if (loginForm.password.value.length <= 0) {
    		alert("<? echo $langRes["message"][1][$manLangCode] ?>.");
    		loginForm.password.focus();
    		return false;
    	}
    	
        loginForm.submit();
        return true;
    }

</script>
</head>

<body onload="loginForm.password.focus();">
<div class="container">

    <div class="contents">
        <div class="contents-inner" style="height: 450px;">
		<!-- 내용-시작 -->

<form id="loginForm" name="loginForm" method="post" action="./login.php">

<div style="position:relative; left:220px; top:100px; width: 507px; height:342px; background-image: url(./image/loginbg.png)">
<image src="./image/ci.png" style="position:absolute; left:193px; top:0px;"/>
<label class="label-item" style="position:absolute; left:15px; top:184px;"><? echo $langRes["label"][0][$manLangCode] ?></label>
<label class="label-item" style="position:absolute; left:15px; top:225px;"><? echo $langRes["label"][1][$manLangCode] ?></label>
<input class="input-text" name="userid" type="text" maxlength="30" value="admin" style="position:absolute; left:120px; top:175px;" />
<input class="input-text" name="password" type="password" maxlength="60" value="admin" style="position:absolute; left:120px; top:216px;" />
<img style="position:absolute; left:380px; top:175px; cursor:pointer;" src="./image/<? echo $manLangCode ?>/login.png" onclick="onLogin();"/>
<label class="label-desc" style="position:absolute; left:120px; top:270px;"><? echo $langRes["label"][2][$manLangCode] ?></label>
<label class="label-desc" style="position:absolute; left:120px; top:290px;">TEL. 02-0000-0000</label>
</div>

</form>


		<!-- 내용-끝 -->
       </div> <!-- <div class="contents-inner"> -->
    </div> <!-- <div class="contents"> -->

    <div class="copyright">
        <div class="copyright-inner">
			<!-- copyright -->
			<?php include 'copyright.php'; ?>
        </div>
    </div> <!-- <div class="copyright"> -->

</div> <!-- <div class="container"> -->

</body>
</html>
