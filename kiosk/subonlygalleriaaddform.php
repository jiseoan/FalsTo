<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$dftLang = 1;	// 한국어
$idlangs = null;
$nmlangs = null;
$nidlangs = 0;

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$db->close();
}
?>


<html>
<head>
<title>
Only Galleria 관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addForm.action='subonlygalleriaadd.php'; addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subonlygalleriaman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1">속성</th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'<input type="hidden" name="idlang[]" value="'.$idlangs[$i].'"/></th>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">썸네일</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:95%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="paththumb" /></td>
<?
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">텍스트</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:95%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="pathtextimg" /></td>
<?
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">이미지</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:95%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="pathimg" /></td>
<?
}
?>
</tr>

</table>
</form>
</div>

</body>
</html>
