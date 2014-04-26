<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$arrCtx = array("itm_kwrd"=>"",
				"itmsrtattr"=>"relicnumber",
				"itmsrtodr"=>"asc");

foreach ($arrCtx as $k=>$v) {
	if (array_key_exists($k, $_GET)) {
		$arrCtx[$k] = $_GET[$k];
	}
	else if (array_key_exists($k, $_POST)) {
		$arrCtx[$k] = $_POST[$k];
	}
	else if (array_key_exists($k, $_SESSION)) {
		$arrCtx[$k] = $_SESSION[$k];
		continue;
	}
	$_SESSION[$k] = $arrCtx[$k];
}

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$dftLang = $_SESSION['manLang'];
$maxitem = 10;
$dspattrs = array("relicnumber", "fullname", "nationera", "institution", "designation"); // 유물번호, 정식이름, 국적, 소장기관, 지정구분
$ndspattrs = count($dspattrs);
$iditems = null;
$niditems = 0;
$attritems = array();
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$maxpgno = 1;
$srtattr = array("relicnumber"=>"relicnumber", "fullname"=>"fullname", "nationera"=>"nationera", "institution"=>"institution", "designation"=>"designation");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "select distinct i.iditem FROM t_item as i";
	$str .= " left join t_itemext as s on (i.iditem = s.iditem and s.idlang = ".$dftLang." and s.idattr = '".$srtattr[$arrCtx["itmsrtattr"]]."')";
	if (strlen($arrCtx["itm_kwrd"]) > 0) {
		$str .= " left join t_itemext as k on (i.iditem = k.iditem and k.idlang = ".$dftLang.")";
		$str .= " where k.attrval like '%".$arrCtx["itm_kwrd"]."%'";
	}
	$str .= " order by s.attrval ".$arrCtx["itmsrtodr"].", i.iditem;";
	
	$iditems = $db->getSingleList($str, "iditem");
	$nallitems = count($iditems);

	$maxpgno = ceil($nallitems / $maxitem);
	if ($maxpgno <= 0) {
		$maxpgno = 1;
	}
	if ($curpage > $maxpgno) {
		$curpage = $maxpgno;
		$offset = ($curpage - 1) * $maxitem;
	}

	if ($nallitems > 0) {
		$niditems = ($offset + $maxitem > $nallitems) ? ($nallitems - $offset) : ($maxitem);
		for ($i = 0 ; $i < $niditems ; $i++) {
			$attritems[$i] = array();
			for ($j = 0 ; $j < $ndspattrs ; $j++) {
				$str = "SELECT ifnull(attrval,'') as attrval FROM t_itemext where iditem = '".$iditems[$offset + $i]."' and idattr = '".$dspattrs[$j]."' and idlang = ".$dftLang.";";
				$n = $db->querySelect($str);
				if ($n == 1) {
					$row = $db->goNext();;
					$attritems[$i][$j] = $row['attrval'];
				}
				else {
					$attritems[$i][$j] = '';
				}
				$db->free();
			}
		}
		$niditems = count($attritems);
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

<form id="regForm" name="regForm" method="post" action="">

<image src="./image/<? echo $manLangCode ?>/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="regForm.action='subitemaddform.php'; regForm.submit();"/>
<image src="./image/<? echo $manLangCode ?>/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subitemremove.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="if (testCheckbox('del[]')) { regForm.action='subitemupdateform.php'; regForm.submit(); }"/>

<!-- 검색 -->
<input type="text" name="itm_kwrd" maxlength="50" style="position:relative; left:578px; top:5px; width: 150px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $arrCtx["itm_kwrd"]; ?>"/>
<image src="./image/<? echo $manLangCode ?>/search.png" style="position:relative; left:578px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="검색" onclick="regForm.action='subitemman.php'; regForm.submit();"/>


<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<th style="width:100px;" class="th2"><? echo $langRes["label"][0][$manLangCode] ?></th>
<?
if ($arrCtx['itmsrtattr'] == "relicnumber") {
	if ($arrCtx['itmsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:160px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?itmsrtattr=relicnumber&itmsrtodr='.$odr.'\';">'.$langRes["label"][1][$manLangCode].$imghtml.'</th>';

if ($arrCtx['itmsrtattr'] == "fullname") {
	if ($arrCtx['itmsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:160px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?itmsrtattr=fullname&itmsrtodr='.$odr.'\';">'.$langRes["label"][2][$manLangCode].$imghtml.'</th>';

if ($arrCtx['itmsrtattr'] == "nationera") {
	if ($arrCtx['itmsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '&nbsp;<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '&nbsp;<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:160px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?itmsrtattr=nationera&itmsrtodr='.$odr.'\';">'.$langRes["label"][3][$manLangCode].$imghtml.'</th>';

if ($arrCtx['itmsrtattr'] == "institution") {
	if ($arrCtx['itmsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:160px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?itmsrtattr=institution&itmsrtodr='.$odr.'\';">'.$langRes["label"][4][$manLangCode].$imghtml.'</th>';

if ($arrCtx['itmsrtattr'] == "designation") {
	if ($arrCtx['itmsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:160px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?itmsrtattr=designation&itmsrtodr='.$odr.'\';">'.$langRes["label"][5][$manLangCode].$imghtml.'</th>';
?>
</tr>
<?php
for ($i = 0 ; $i < $niditems ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $iditems[$i]; ?>"/><input type="hidden" name="iditem[]" value="<? echo $iditems[$i]; ?>"/></td>
<td style="width:100px; text-align:center;" class="td2"><? echo ($offset + $i + 1); ?></td>
<td style="width:160px; text-align:center;" class="td2"><? echo htmlspecialchars($attritems[$i][0]); ?></td>
<td style="width:160px; text-align:center;" class="td2"><? echo htmlspecialchars($attritems[$i][1]); ?></td>
<td style="width:160px; text-align:center;" class="td2"><? echo htmlspecialchars($attritems[$i][2]); ?></td>
<td style="width:160px; text-align:center;" class="td2"><? echo htmlspecialchars($attritems[$i][3]); ?></td>
<td style="width:160px; text-align:center;" class="td2"><? echo htmlspecialchars($attritems[$i][4]); ?></td>
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
