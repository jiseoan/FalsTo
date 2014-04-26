<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$arrCtx = array("sort"=>"asc");

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
<?php include './common/env.php'; ?>
<?php include './common/file.php'; ?>
<?php
$maxitem = 5;
$cpdirs = array();
$offset = ($curpage - 1) * $maxitem;

if ($curpage <= 0) {
	$curpage = 1;
}

$dirs = DirsInDir($backup_dir);
$nallitems = count($dirs);

if ($arrCtx["sort"] == "asc") {
	sort($dirs);
}
else {
	rsort($dirs);
}

for ($i = $nallitems - $offset - 1, $j = 0 ; $j < $maxitem && $i >= 0 ; $i--, $j++) {
	$cpdirs[$j] = $dirs[$i];
}

$ncpdirs = count($cpdirs);
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/backup.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="백업" onclick="worktitle.innerHTML = '<? echo $langRes["label"][0][$manLangCode] ?>'; loadingview.style.visibility = 'visible'; document.location.replace('./maintenance/backup.php?manpage=subrestoreman');"/>
<image src="./image/<? echo $manLangCode ?>/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subbackupremove.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/restore.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="복원" onclick="if (testCheckbox('del[]')) { worktitle.innerHTML = '<? echo $langRes["label"][1][$manLangCode] ?>'; loadingview.style.visibility = 'visible'; regForm.action='subrestore.php'; regForm.submit(); }"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<th style="width:900px; cursor:pointer;" class="th2" onclick="document.location = 'subrestoreman.php?sort=<? echo ($arrCtx['sort'] == "asc" ? "desc" : "asc"); ?>';"><? echo $langRes["label"][2][$manLangCode] ?>&nbsp;<image src="./image/<? echo ($arrCtx['sort'] == "asc" ? "sort" : "sortr"); ?>.png" style="cursor:pointer;"/></th>
</tr>
<?php
for ($i = 0 ; $i < $ncpdirs ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $cpdirs[$i]; ?>"/></td>
<td style="width:900px; text-align:center;" class="td2"><? echo $cpdirs[$i]; ?></td>
</tr>
<?php
}
?>
</table>
</form>

<!-- 페이지 목록 -->
<?php include 'pages.php'; ?>

<!-- 로딩 화면 -->
<div id="loadingview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<div style="position:absolute; width: 100%; top:50%; text-align:center;"><label id="worktitle"></label></div>
</div>


</body>
</html>
