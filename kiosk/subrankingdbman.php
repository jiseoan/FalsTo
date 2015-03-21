<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$rnkdbip = "127.0.0.1";
$rnkdbport = 3306;

if ($ok) {
	$str = "SELECT idoperating, rankingdbip, rankingdbport FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$idoperating = $row['idoperating'];
		$rnkdbip = $row['rankingdbip'];
		$rnkdbport = $row['rankingdbport'];
	}

	$db->free();

	$db->close();
}

?>



<html>
<head>
<title>
랭킹서버정보관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="subrankingdbupdate.php">
<input type="hidden" name="idoperating" value="<? echo $idoperating; ?>"/>
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:345px;" class="th1">속성</th>
<th style="width:600px;" class="th2">설정</th>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">랭킹서버-IP</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rnkdbip" maxlength="15" style="width: 150px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rnkdbip; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">랭킹서버-PORT</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rnkdbport" maxlength="15" style="width: 150px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rnkdbport; ?>"/></td>
</tr>
</table>
</form>


</body>
</html>
