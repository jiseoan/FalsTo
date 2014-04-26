<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$dirprfx = "./images/";
if (!isset($_GET["path"]) || !is_dir($dirprfx.$_GET["path"])) {
	echo "error -> path<br/>";
	exit;
}

$arrCtx = array("path"=>"",
				"add"=>"no",
				"del"=>"no",
				"dir"=>"no",
				"sort"=>"asc");
$envfrfx = $_GET["path"];

foreach ($arrCtx as $k=>$v) {
	if (array_key_exists($k, $_GET)) {
		$arrCtx[$k] = $_GET[$k];
	}
	else if (array_key_exists($k, $_POST)) {
		$arrCtx[$k] = $_POST[$k];
	}
	else {
		$k2 = $envfrfx.$k;
		if (array_key_exists($k2, $_SESSION)) {
			$arrCtx[$k] = $_SESSION[$k2];
		}
		continue;
	}

	$k2 = $envfrfx.$k;
	$_SESSION[$k2] = $arrCtx[$k];
}

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
if ($curpage <= 0) {
	$curpage = 1;
}
?>
<?php include './common/file.php'; ?>
<?php
$maxitem = 5;
$namegetext = "?path=".$arrCtx['path'];
$baseimgpath = $dirprfx.$arrCtx['path'];
$imgs = filesInDir($baseimgpath);
$nimgs = count($imgs);
$offset = ($curpage - 1) * $maxitem;
$nallitems = $nimgs;

if ($arrCtx["sort"] == "asc") {
	sort($imgs);
}
else {
	rsort($imgs);
}

$cpimgs = array();
for ($i = $offset, $j = 0 ; $j < $maxitem && $i < $nallitems ; $i++, $j++) {
	$cpimgs[$j] = iconv("euc-kr", "utf-8", $imgs[$i]);
}

$ncpimgs = count($cpimgs);
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

<?php
$leftoffset = 0;
if ($arrCtx['add'] == "yes") {
	echo '<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addview.style.visibility = \'visible\';"/>';
	$leftoffset += 5;
}
if ($arrCtx['del'] == "yes") {
	echo '<image src="./image/remove.png" style="position:relative; left:'.$leftoffset.'px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox(\'del[]\')) { regForm.action=\'subimagesremove.php'.$namegetext.'\'; regForm.submit(); }"/>';
	$leftoffset += 5;
}
?>
<image src="./image/change.png" style="position:relative; left:<? echo $leftoffset; ?>px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="loadingview.style.visibility = 'visible'; regForm.action='subimagesupdate.php<? echo $namegetext; ?>'; regForm.submit();"/>

<form id="regForm" name="regForm" method="post" enctype="multipart/form-data" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?php
if ($arrCtx['dir'] == "yes") {
	echo '<th style="width:200px;" class="th2">경로</th>';
	$imgtabwidth = 700;
}
else {
	$imgtabwidth = 900;
}
echo '<th style="width:'.$imgtabwidth.'px; cursor:pointer;" class="th2" onclick="document.location = \'subimagesman.php?path='.$arrCtx['path'].'&sort='.($arrCtx['sort'] == "asc" ? "desc" : "asc").'\';">이미지&nbsp;<image src="./image/'.($arrCtx['sort'] == "asc" ? "sort" : "sortr").'.png" style="cursor:pointer;"/></th>';
?>
</tr>
<?php
$dirnameoffset = strlen($baseimgpath) + 1;
for ($i = 0 ; $i < $ncpimgs ; $i++) {
	$path2 = pathinfo($cpimgs[$i]);
?>
<tr style="height:120px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $cpimgs[$i]; ?>"/></td>
<?php
if ($arrCtx['dir'] == "yes") {
	echo '<td style="width:200px; text-align:center;" class="td2">'.substr($path2['dirname'], $dirnameoffset).'</td>';
}
echo '<td style="width:'.$imgtabwidth.'px; text-align:center;" class="td2"><a href="'.$cpimgs[$i].'"><img style="height:80px;" src="'.$cpimgs[$i].'" alt=""/></a><br/>'.$path2['basename'].'<br/><input type="file" name="file[]" style="width:95%; border-style:none;"/><input type="hidden" name="orgpath[]" value="'.$cpimgs[$i].'" /></td>';
?>
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

<form id="addForm" name="addForm" method="post" enctype="multipart/form-data" action="subimagesadd.php<? echo $namegetext; ?>">
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
