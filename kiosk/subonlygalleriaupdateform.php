<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$del = $_POST["del"];
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$idonlygalleria = $del[0];
$seqno = '';
$idlangs = null;
$nmlangs = null;
$nidlangs = 0;
$paththumb = array();
$pathtextimg = array();
$pathimg = array();

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	for ($j = 0 ; $j < $nidlangs ; $j++) {
		$str = "SELECT seqno, paththumb, pathtextimg, pathimg FROM t_onlygalleria where idonlygalleria = ".$idonlygalleria." and idlang =  ".$idlangs[$j].";";
		$n = $db->querySelect($str);
		if ($n == 1) {
			$row = $db->goNext();;
			$seqno = $row['seqno'];
			$paththumb[$j] = $row['paththumb'];
			$pathtextimg[$j] = $row['pathtextimg'];
			$pathimg[$j] = $row['pathimg'];
		}
		else {
			$paththumb = '';
			$pathtextimg = '';
			$pathimg = '';
		}
		$db->free();
	}

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

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subonlygalleriaman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="subonlygalleriaupdate.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="idonlygalleria" value="<? echo $idonlygalleria; ?>" />
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
<td style="width:145px; text-align:center;" class="td1">순번</td>
<td colspan="<? echo $nidlangs ?>" style="width:800px; text-align:left;" class="td2"><input type="text" name="seqno" maxlength="5" style="position:relative; top:5px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $seqno; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">썸네일</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2">
<?
	if (strlen($paththumb[$i]) > 0) {
?>
		<a href="<? echo $paththumb[$i]; ?>" target="new"><img style="position:relative; top:3px; height:20px;" src="<? echo $paththumb[$i]; ?>" alt=""/></a>
<?
	}
?><input type="file" name="file[]" style="width:80%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="paththumb" /><input type="hidden" name="orgfile[]" value="<? echo $paththumb[$i]; ?>" /></td>
<?
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">텍스트</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2">
<?
	if (strlen($pathtextimg[$i]) > 0) {
?>
		<a href="<? echo $pathtextimg[$i]; ?>" target="new"><img style="position:relative; top:3px; height:20px;" src="<? echo $pathtextimg[$i]; ?>" alt=""/></a>
<?
	}
?><input type="file" name="file[]" style="width:80%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="pathtextimg" /><input type="hidden" name="orgfile[]" value="<? echo $pathtextimg[$i]; ?>" /></td>
<?
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">이미지</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
?>
	<td style="width:200px; text-align:center;" class="td2">
<?
	if (strlen($pathimg[$i]) > 0) {
?>
		<a href="<? echo $pathimg[$i]; ?>" target="new"><img style="position:relative; top:3px; height:20px;" src="<? echo $pathimg[$i]; ?>" alt=""/></a>
<?
	}
?><input type="file" name="file[]" style="width:80%; border-style:none;"/><input type="hidden" name="idlang[]" value="<? echo $idlangs[$i] ?>" /><input type="hidden" name="attrfile[]" value="pathimg" /><input type="hidden" name="orgfile[]" value="<? echo $pathimg[$i]; ?>" /></td>
<?
}
?>
</tr>
</table>
</form>
</div>

</body>
</html>
