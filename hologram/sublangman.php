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
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>
<!--<image src="./image/<? echo $manLangCode ?>/update.png" style="position:absolute; left:100px; top:59px; width: 70px; height:32px; cursor:pointer;" alt="업데이트" onclick="updateClicked()"/>
-->

<form id="regForm" name="regForm" method="post" enctype="multipart/form-data" action="./sublangupdate.php">
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1"><? echo $langRes["label"][0][$manLangCode] ?></th>
<th style="width:800px;" class="th2"><? echo $langRes["label"][1][$manLangCode] ?></th>
</tr>
<?php
if ($ok) {
//	$str = "SELECT l.idlang, name, ifnull(consonantseq, '') as conseq, ifnull(menuimage, '') as img FROM t_lang as l left join t_envlang as e on (e.idlang = l.idlang);";
	$str = "SELECT l.idlang, name, ifnull(menuimage, '') as img FROM t_lang as l left join t_envlang as e on (e.idlang = l.idlang);";
	$n = $db->querySelect($str);
	for ($i = 0 ; $i < $n ; $i++)
	{
		$row = $db->goNext();
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $row['name']; ?><input type="hidden" name="idlang[]" value="<? echo $row['idlang']; ?>"/></td>
<td style="width:800px; text-align:center;" class="td2"><a href="<? echo $row['img']; ?>"><image src="<? echo $row['img']; ?>" style="position:relative; top:5px; width:20px; height:20px; cursor:pointer;" alt="버튼이미지"></a>&nbsp;<input type="file" name="file[]" style="width:750px; border-style:none;"/></td>
</tr>
<?php
	}
	$db->free();
}
?>
</table>
</form>


</body>
</html>

<?php
if ($ok) {
	$db->close();
}
?>
