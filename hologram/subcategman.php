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

$maxitem = 5;
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$idlangs = null;
$nmlangs = null;
$idgrps = null;
$names = array();
$seqs = array();
$nidgrps = 0;
$nidlangs = 0;
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$maxpgno = 1;

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT distinct idgrp FROM t_grp where idgrpcateg = 1 order by idgrp;";
	$idgrps = $db->getSingleList($str, "idgrp");
	$nidgrps = count($idgrps);
	$nallitems = $nidgrps;

	$maxpgno = ceil($nallitems / $maxitem);
	if ($maxpgno <= 0) {
		$maxpgno = 1;
	}
	if ($curpage > $maxpgno) {
		$curpage = $maxpgno;
		$offset = ($curpage - 1) * $maxitem;
	}

	for ($i = $offset, $niditems = min($offset + $maxitem, $nidgrps) ; $i < $niditems ; $i++) {
		$names[$i] = array();
		$seqs[$i] = '';
		for ($j = 0 ; $j < $nidlangs ; $j++) {
			$names[$i][$j] = '';
		}

		$str = "SELECT seq, idlang, name FROM t_grp where idgrp = ".$idgrps[$i].";";
		$n = $db->querySelect($str);
		for ($j = 0 ; $j < $n ; $j++) {
			$row = $db->goNext();
			$seqs[$i] = $row['seq'];
			$k = $db->lookupIndex($idlangs, $nidlangs, $row['idlang']);
			$names[$i][$k] = stripslashes($row['name']);
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

<image src="./image/<? echo $manLangCode ?>/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="regForm.action='subcategadd.php'; regForm.submit();"/>
<image src="./image/<? echo $manLangCode ?>/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subcategremove.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.action='subcategupdate.php'; regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
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
	for ($i = $offset ; $i < $niditems ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $idgrps[$i]; ?>"/><input type="hidden" name="idgrp[]" value="<? echo $idgrps[$i]; ?>"/></td>
<td style="width:100px; text-align:center;" class="td2"><input type="text" name="seq[]"  maxlength="30" style="width: 100%; border-style:none; text-align:center;" value="<? echo $seqs[$i]; ?>"/></td>
<?php
		for ($j = 0 ; $j < $nidlangs ; $j++) {
?>
<td style="width:200px; text-align:center;" class="td2"><input type="text" name="grpname[]"  maxlength="30" style="width: 100%; border-style:none; text-align:center;" value="<? echo $names[$i][$j]; ?>"/></td>
<?php
		}
?>
</tr>
<?php
	}
?>
</table>
</form>


<!-- 페이지 목록 -->
<?php include 'pages.php'; ?>


</body>
</html>
