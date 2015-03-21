<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

if (!isset($_SESSION['userid']) || strlen($_SESSION['userid']) <= 0) {
	header("Location: ./index.php");
	exit();
}

$menuitem = 1;
?>

<html>
<head>
<title>키오스크 관리자페이지 - <? echo $_SESSION['userid']; ?></title>
<link rel="stylesheet" type="text/css" href="./css/mainpage.css" />
</head>

<body>
<div class="container">

    <div class="menu-bar">
	    <div class="menu-bar-inner">
			<!-- 메뉴 -->
			<?php include 'menu.php'; ?>
	    </div>
    </div> <!-- <div class="menu-bar"> -->

    <div class="contents">
        <div class="contents-inner" style="height: 7350px;">
		<!-- 내용-시작 -->



<!-- 타이틀 -->
<div style="position:relative; left:0px; top:30px; width: 100%; height:22px;">
<img src="./image/bulletlt.png"/><label class="main-title" style="position:absolute; left:32px; top:3px;">컨텐츠관리</label>
</div>

<div style="position:relative; left:0px; top:60px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">슬라이드관리</label>
</div>
<iframe style="position:relative; left:0px; top:60px; width: 945px; height:790px;" src="submainslideman.php" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:90px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">슬라이드관리-GOURMET494</label>
</div>
<iframe style="position:relative; left:0px; top:90px; width: 945px; height:790px;" src="submainslideman.php?name=gourmet494" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:120px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">브랜드관리</label>
</div>
<iframe style="position:relative; left:0px; top:120px; width: 945px; height:800px;" src="subitemman.php" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:150px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">FOOD MENU 관리</label>
</div>
<iframe style="position:relative; left:0px; top:150px; width: 945px; height:800px;" src="subfoodmenuman.php" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:180px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">쇼핑정보관리</label>
</div>
<iframe style="position:relative; left:0px; top:180px; width: 945px; height:800px;" src="subshoppinginfoman.php" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:210px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">TASTY CHART 관리</label>
</div>
<iframe style="position:relative; left:0px; top:210px; width: 945px; height:800px;" src="subtastychartman.php" frameborder="0" scrolling="auto"></iframe>

<div style="position:relative; left:0px; top:240px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">Only Galleria 관리</label>
</div>
<iframe style="position:relative; left:0px; top:240px; width: 945px; height:650px;" src="subonlygalleriaman.php" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:270px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">UI관리</label>
</div>
<iframe style="position:relative; left:0px; top:270px; width: 945px; height:790px;" src="subimagesman.php?path=static&add=no&del=no&dir=yes" frameborder="0" scrolling="no"></iframe>

<div style="position:relative; left:0px; top:300px; width: 945px; height:44px; background-image: url(./image/subtitlebg.png); background-repeat: no-repeat;">
<label class="sub-title" style="position:absolute;left:32px; top:15px;" class="subtitle">카테고리관리</label>
</div>
<iframe style="position:relative; left:0px; top:300px; width: 945px; height:380px;" src="subcategman.php" frameborder="0" scrolling="no"></iframe>

		<!-- 내용-끝 -->
       </div> <!-- <div class="contents-inner"> -->
    </div> <!-- <div class="contents"> -->

    <div class="copyright">
        <div class="copyright-inner">
			<!-- copyright -->
			<?php include 'copyright.php'; ?>
        </div>
    </div> <!-- <div class="copyright"> -->

</div> <!-- <div class="container"> -->

</body>
</html>
