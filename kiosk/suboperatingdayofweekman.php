<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$idoperating = 0;
$holimon = 'N';
$holitue = 'N';
$holiwed = 'N';
$holithr = 'N';
$holifri = 'N';
$holisat = 'N';
$holisun = 'N';

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_operating where enable = 'Y';";
	if ($db->queryCount($str, 'cnt') == 0) {
		$str = "insert into t_operating (holimon) values ('".$holimon."');";
		$db->query($str);
	}

	$str = "SELECT idoperating, holimon, holitue, holiwed, holithr, holifri, holisat, holisun FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$idoperating = $row['idoperating'];
		$holimon = $row['holimon'];
		$holitue = $row['holitue'];
		$holiwed = $row['holiwed'];
		$holithr = $row['holithr'];
		$holifri = $row['holifri'];
		$holisat = $row['holisat'];
		$holisun = $row['holisun'];
	}

	$db->free();

	$db->close();
}

?>


<html>
<head>
<title>
업데이트관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<form id="regForm" name="regForm" method="post" action="suboperatingdayofweekupdate.php">
<input type="hidden" name="idoperating" value="<? echo $idoperating; ?>"/>

<div style="position:relative; left:0px; top:0px; width:945px; height:52px; font-size: 14px;">
<input type="checkbox" name="holi[]" <? echo $holimon == 'Y' ? "checked" : ""; ?> class="chkbox" value="holimon" style="position:relative; left:0px; top:5px;"/>월&nbsp;
<input type="checkbox" name="holi[]" <? echo $holitue == 'Y' ? "checked" : ""; ?> class="chkbox" value="holitue" style="position:relative; left:0px; top:5px;"/>화&nbsp;
<input type="checkbox" name="holi[]" <? echo $holiwed == 'Y' ? "checked" : ""; ?> class="chkbox" value="holiwed" style="position:relative; left:0px; top:5px;"/>수&nbsp;
<input type="checkbox" name="holi[]" <? echo $holithr == 'Y' ? "checked" : ""; ?> class="chkbox" value="holithr" style="position:relative; left:0px; top:5px;"/>목&nbsp;
<input type="checkbox" name="holi[]" <? echo $holifri == 'Y' ? "checked" : ""; ?> class="chkbox" value="holifri" style="position:relative; left:0px; top:5px;"/>금&nbsp;
<input type="checkbox" name="holi[]" <? echo $holisat == 'Y' ? "checked" : ""; ?> class="chkbox" value="holisat" style="position:relative; left:0px; top:5px;"/>토&nbsp;
<input type="checkbox" name="holi[]" <? echo $holisun == 'Y' ? "checked" : ""; ?> class="chkbox" value="holisun" style="position:relative; left:0px; top:5px;"/>일&nbsp;
<image src="./image/change.png" style="position:relative; left:30px; top:10px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>
</div>
</form>


</body>
</html>
