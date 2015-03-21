<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$scrsaverintv = 5;
$updateintv = 5;
$rollheader = 10;
$rollimgslider = 10;
$rollshopinfoticker = 10;
$rollranking = 10;

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_operating where enable = 'Y';";
	if ($db->queryCount($str, 'cnt') == 0) {
		$str = "insert into t_operating (hourbegin) values ('".$hourbegin."');";
		$db->query($str);
	}

	$str = "SELECT idoperating, scrsaverintv, updateintv, rollheader, rollimgslider, rollshopinfoticker, rollranking FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$idoperating = $row['idoperating'];
		$scrsaverintv = $row['scrsaverintv'];
		$updateintv = $row['updateintv'];
		$rollheader = $row['rollheader'];
		$rollimgslider = $row['rollimgslider'];
		$rollshopinfoticker = $row['rollshopinfoticker'];
		$rollranking = $row['rollranking'];
	}

	$db->free();

	$db->close();
}

?>



<html>
<head>
<title>
환경설정관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="subconfigureupdate.php">
<input type="hidden" name="idoperating" value="<? echo $idoperating; ?>"/>
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:345px;" class="th1">속성</th>
<th style="width:600px;" class="th2">설정</th>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">스크린세이버 동작</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="scrsaverintv" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $scrsaverintv; ?>"/>분</td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">업데이트 간격</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="updateintv" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $updateintv; ?>"/>분</td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">헤드 롤링간격</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rollheader" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rollheader; ?>"/>초</td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">이미지슬라이드 롤링간격</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rollimgslider" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rollimgslider; ?>"/>초</td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">쇼핑정보티커 롤링간격</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rollshopinfoticker" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rollshopinfoticker; ?>"/>초</td>
</tr>
<tr style="height:42px;">
<td style="width:345px; text-align:right; padding-right:10px;" class="td1">랭킹 롤링간격</td>
<td style="width:600px; text-align:left; padding-left:10px;" class="td2"><input type="text" name="rollranking" maxlength="5" style="width: 50px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $rollranking; ?>"/>초</td>
</tr>
</table>
</form>


</body>
</html>
