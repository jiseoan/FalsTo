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

$idlangs = null;
$nmlangs = null;
$idattrs = null;
$attrnames = array();
$nidattrs = 0;
$nidlangs = 0;

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT distinct idattr FROM t_itemattr order by idattr;";
	$idattrs = $db->getSingleList($str, "idattr");
	$nidattrs = count($idattrs);

	for ($i = 0 ; $i < $nidattrs ; $i++) {
		$attrnames[$i] = array();
		for ($j = 0 ; $j < $nidlangs ; $j++) {
			$attrnames[$i][$j] = '';
		}

		$str = "SELECT idlang, name FROM t_itemattr where idattr = '".$idattrs[$i]."';";
		$n = $db->querySelect($str);
		for ($j = 0 ; $j < $n ; $j++) {
			$row = $db->goNext();
			$k = $db->lookupIndex($idlangs, $nidlangs, $row['idlang']);
			$attrnames[$i][$k] = $row['name'];
		}
		$db->free();
	}

	$db->close();
}
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="document.location.replace('subitemattradd.php');"/>
<image src="./image/<? echo $manLangCode ?>/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subitemattrremove.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.action='subitemattrupdate.php'; regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="">
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<th style="width:100px;" class="th2"><? echo $langRes["label"][0][$manLangCode] ?></th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'<input type="hidden" name="idlang[]" value="'.$idlangs[$i].'"/></th>';
}
?>
</tr>
<?php
	for ($i = 0 ; $i < $nidattrs ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $idattrs[$i]; ?>"/><input type="hidden" name="idattr[]" value="<? echo $idattrs[$i]; ?>"/></td>
<td style="width:100px; text-align:center;" class="td2"><input type="text" name="idattrnew[]"  maxlength="30" style="width: 100%; border-style:none; text-align:center;" value="<? echo $idattrs[$i]; ?>"/></td>
<?php
		for ($j = 0 ; $j < $nidlangs ; $j++) {
?>
<td style="width:200px; text-align:center;" class="td2"><input type="text" name="attrname[]"  maxlength="30" style="width: 100%; border-style:none; text-align:center;" value="<? echo $attrnames[$i][$j]; ?>"/></td>
<?php
		}
?>
</tr>
<?php
	}
?>
</table>
</form>

</body>
</html>
