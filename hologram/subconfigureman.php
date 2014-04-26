<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$scrsaverintv = 5;
$updateintv = 5;

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_operating where enable = 'Y';";
	if ($db->queryCount($str, 'cnt') == 0) {
		$str = "insert into t_operating (hourbegin) values ('".$hourbegin."');";
		$db->query($str);
	}

	$str = "SELECT idoperating, scrsaverintv, updateintv, showtime FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$idoperating = $row['idoperating'];
		$scrsaverintv = $row['scrsaverintv'];
		$updateintv = $row['updateintv'];
		$showtime = $row['showtime'];
	}

	$db->free();

	$db->close();
}

?>



<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="subconfigureupdate.php">
<input type="hidden" name="idoperating" value="<? echo $idoperating; ?>"/>
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:345px;" class="th1"><? echo $langRes["label"][0][$manLangCode] ?></th>
<th style="width:600px;" class="th2"><? echo $langRes["label"][1][$manLangCode] ?></th>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1"><? echo $langRes["label"][2][$manLangCode] ?></td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="scrsaverintv" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $scrsaverintv; ?>"/><? echo $langRes["label"][3][$manLangCode] ?></td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1"><? echo $langRes["label"][4][$manLangCode] ?></td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="updateintv" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $updateintv; ?>"/><? echo $langRes["label"][5][$manLangCode] ?></td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1"><? echo $langRes["label"][6][$manLangCode] ?></td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="showtime" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $showtime; ?>"/><? echo $langRes["label"][7][$manLangCode] ?></td>
</tr>
</table>
</form>


</body>
</html>
