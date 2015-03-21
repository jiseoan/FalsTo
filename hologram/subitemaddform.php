<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$dftLang = $_SESSION['manLang'];
$idlangs = null;
$nmlangs = null;
$idgrps = null;
$names = array();
$nidgrps = 0;
$nidlangs = 0;
$attrnames = array();

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT distinct idgrp FROM t_grp where idgrpcateg = 1 order by idgrp;";
	$idgrps = $db->getSingleList($str, "idgrp");
	$nidgrps = count($idgrps);

	for ($i = 0 ; $i < $nidgrps ; $i++) {
		$names[$i] = array();
		for ($j = 0 ; $j < $nidlangs ; $j++) {
			$names[$i][$j] = '';
		}

		$str = "SELECT idlang, name FROM t_grp where idgrp = ".$idgrps[$i]." order by seq;";
		$n = $db->querySelect($str);
		for ($j = 0 ; $j < $n ; $j++) {
			$row = $db->goNext();
			$k = $db->lookupIndex($idlangs, $nidlangs, $row['idlang']);
			$names[$i][$k] = $row['name'];
		}
		$db->free();
	}

	$str = "SELECT distinct idattr FROM t_itemattr order by idattr;";
	$idattrs = $db->getSingleList($str, "idattr");
	$nidattrs = count($idattrs);

	for ($i = 0 ; $i < $nidattrs ; $i++) {
		$str = "SELECT name FROM t_itemattr where idattr = '".$idattrs[$i]."' and idlang = ".$dftLang.";";
		$n = $db->querySelect($str);
		if ($n == 1) {
		  $row = $db->goNext();
		  $attrnames[$i] = $row['name'];
		}
		else {
		  $attrnames[$i] = $idattrs[$i];
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
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addForm.action='subitemadd.php'; addForm.submit();"/>
<image src="./image/<? echo $manLangCode ?>/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subitemman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1"><? echo $langRes["label"][0][$manLangCode] ?></th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'<input type="hidden" name="idlang[]" value="'.$idlangs[$i].'"/></th>';
}
?>
</tr>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $langRes["label"][1][$manLangCode] ?></td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="path3ddata" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $langRes["label"][2][$manLangCode] ?></td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="pathimage" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $langRes["label"][3][$manLangCode] ?></td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="paththumbnail" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $langRes["label"][4][$manLangCode] ?></td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><select name="conssel[]" onchange="selectionSync(this);" style="width:150px;">';
	for ($j = 0 ; $j < $nidgrps ; $j++) {
    if ($j == 0) {
	    echo '<option value="'.$idgrps[$j].'" selected>'.$names[$j][$i].'</option>';
    }
    else {
	    echo '<option value="'.$idgrps[$j].'">'.$names[$j][$i].'</option>';
    }
  }
	echo '</select><input type="hidden" name="attrsel[]" value="idgrp" /><input type="hidden" name="idlangsel[]" value="'.$idlangs[$i].'" /></td>';
}
?>
</tr>
<?php
for ($i = 0 ; $i < $nidattrs ; $i++) {
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1"><? echo $attrnames[$i]; ?></td>
<?php
  for ($j = 0 ; $j < $nidlangs ; $j++) {
?>
<td style="width:160px; text-align:center;" class="td2">
<?php
	if (strcmp("imgdesc", $idattrs[$i]) == 0) {
?>
<input type="file" name="file2[]" style="width:150px; border-style:none;"/>
<input type="hidden" name="attrfile2[]" value="imgdesc" /><input type="hidden" name="idlang2[]" value="<? echo $idlangs[$j]; ?>" /></td>
<?php
	}
	else {
?>
<textarea name="cons[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea>
<input type="hidden" name="attr[]" value="<? echo $idattrs[$i]; ?>" /><input type="hidden" name="idlang[]" value="<? echo $idlangs[$j]; ?>" /></td>
<?php
	}
  }
?>
</tr>
<?php
}
?>
</table>
</form>
</div>

</body>
</html>
