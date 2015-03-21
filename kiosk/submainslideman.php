<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$slidername = isset($_GET["name"]) ? $_GET["name"] : (isset($_POST["name"]) ? $_POST["name"] : "");

$baseimgpath = "";
$tablename = "";

switch ($slidername) {
	case "gourmet494":
		$baseimgpath = "./images/dynamic/gourmet/mainslide";
		$tablename = "t_gourmet494slide";
		break;
	default:
		$baseimgpath = "./images/dynamic/mainslide";
		$tablename = "t_mainslide";
		break;
}

if (!is_dir($baseimgpath)) {
	mkdir($baseimgpath, 0777);
}

$arrCtx = array("mssrtattr"=>"name",
				"mssrtodr"=>"asc");

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
if ($curpage <= 0) {
	$curpage = 1;
}
?>
<?php include './common/file.php'; ?>
<?php include './common/db.php'; ?>
<?php
$maxitem = 5;
$imgs = filesInDir($baseimgpath);
$nimgs = count($imgs);
$offset = ($curpage - 1) * $maxitem;
$nallitems = $nimgs;
$srtattr = array("name"=>"name", "seqno"=>"seqno");

$maxpgno = ceil($nallitems / $maxitem);
if ($maxpgno <= 0) {
	$maxpgno = 1;
}
if ($curpage > $maxpgno) {
	$curpage = $maxpgno;
	$offset = ($curpage - 1) * $maxitem;
}

$ok = $db->open();
if (!$ok) {
	echo "error -> db-open<br/>";
	exit;
}

$str = "update ".$tablename." set flag = 0;";
$db->query($str);

foreach ($imgs as $fpath) {
	$fname = iconv("euc-kr", "utf-8", substr(strrchr($fpath, '/'), 1));
	$str = "insert into ".$tablename." (name, flag) values ('".$fname."', 1);";
	if ($db->query($str) <= 0) {
		$str = "update ".$tablename." set flag = 1 where name = '".$fname."';";
		$db->query($str);
	}
}

$str = "delete from ".$tablename." where flag = 0;";
$db->query($str);

$cpimgs = array();
$str = "SELECT name, seqno FROM ".$tablename." order by ".$srtattr[$arrCtx["mssrtattr"]]." ".$arrCtx["mssrtodr"]." limit ".$offset.",".$maxitem.";";
$ncpimgs = $db->querySelect($str);

for ($i = 0 ; $i < $ncpimgs ; $i++) {
	$row = $db->goNext();
	$cpimgs[$i] = array();
	$cpimgs[$i][0] = $baseimgpath."/".$row['name'];
	$cpimgs[$i][1] = $row['seqno'];
	$cpimgs[$i][2] = $row['name'];
}
$db->free();
$db->close();
?>

<html>
<head>
<title>
이미지관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addview.style.visibility = 'visible';"/>
<image src="./image/remove.png" style="position:relative; left:5px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='submainslideremove.php?name=<? echo $slidername ?>'; regForm.submit(); }"/>
<image src="./image/change.png" style="position:relative; left:10px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="loadingview.style.visibility = 'visible'; regForm.action='submainslideupdate.php?name=<? echo $slidername ?>'; regForm.submit();"/>

<form id="regForm" name="regForm" method="post" enctype="multipart/form-data" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?
if ($arrCtx['mssrtattr'] == "seqno") {
	if ($arrCtx['mssrtodr'] == "asc") {
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
echo '<th style="width:80px; cursor:pointer;" class="th2" onclick="document.location = \'submainslideman.php?name='.$slidername.'&mssrtattr=seqno&mssrtodr='.$odr.'\';">순서'.$imghtml.'</th>';

if ($arrCtx['mssrtattr'] == "name") {
	if ($arrCtx['mssrtodr'] == "asc") {
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
echo '<th style="width:820px; cursor:pointer;" class="th2" onclick="document.location = \'submainslideman.php?name='.$slidername.'&mssrtattr=name&mssrtodr='.$odr.'\';">이미지'.$imghtml.'</th>';
?>
</tr>
<?php
for ($i = 0 ; $i < $ncpimgs ; $i++) {
?>
<tr style="height:120px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $cpimgs[$i][0]; ?>"/></td>
<td style="width:80px; text-align:center;" class="td2"><input type="text" name="seqno[]" maxlength="5" style="position:relative; top:5px; width: 95%; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $cpimgs[$i][1]; ?>"/></td>
<td style="width:820px; text-align:center;" class="td2"><a href="<? echo $cpimgs[$i][0]; ?>"><img style="height:80px;" src="<? echo $cpimgs[$i][0]; ?>" alt=""/></a><br/><? echo $cpimgs[$i][2]; ?><br/><input type="file" name="file[]" style="width:95%; border-style:none;"/><input type="hidden" name="orgpath[]" value="<? echo $cpimgs[$i][0]; ?>" /></td>
</tr>
<?php
}
?>
</table>
</form>

<!-- 페이지 목록 -->
<?php include 'pages.php'; ?>

<!-- 이미지 추가 화면 -->
<div id="addview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="if (addForm.file.value.length < 1) alert('파일을 선택하세요.'); else { loadingview.style.visibility = 'visible'; addForm.submit(); }"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addview.style.visibility = 'hidden';"/>

<form id="addForm" name="addForm" method="post" enctype="multipart/form-data" action="submainslideadd.php?name=<? echo $slidername ?>">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="baseimgpath" value="<? echo $baseimgpath; ?>" />
<input type="file" name="file" style="position:relative; left:0px; top:30px; width:100%; border-style:none;"/>
</form>
</div>

<!-- 로딩 화면 -->
<div id="loadingview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<div style="position:absolute; width: 100%; top:50%; text-align:center;">업로드 중...</div>
</div>


</body>
</html>
