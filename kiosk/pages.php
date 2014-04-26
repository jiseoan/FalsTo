<?PHP
$cpname = basename($_SERVER['PHP_SELF']);
// 페이지당 항목 개수: $maxitem
// 현재 페이지 번호: $curpage
// 전체 항목 개수에 대한 offset: $offset
// 전체 항목 개수 항목 개수: $nallitems

if ($offset < 0) {
  $offset = 0;
}

$maxpages = 10;
$curbeginpgno = floor($offset / ($maxpages * $maxitem)) * $maxpages + 1;
$curendpgno = max(1, $curbeginpgno + $maxpages - 1);
$maxpgno = max(1, ceil($nallitems / $maxitem));

if ($curendpgno > $maxpgno) {
  $curendpgno = $maxpgno;
}
?>

<script type="text/javascript">
function goPage(pageno) {
  pageForm.curpage.value = pageno;
  pageForm.submit();
}
</script>

<form id="pageForm" name="pageForm" method="post" action="<? echo isset($namegetext) ? ($cpname.$namegetext) : $cpname; ?>">

  <input type="hidden" name="curpage" value="1"/>
  <input type="hidden" name="keywords" value="<? echo $keywords; ?>"/>
<div style="position:relative; left:0px; top:35px; width:945px; height:31px; text-align:center;">
<?php
$imgwidth1 = 49;
$imgwidth2 = 32;
$interval1 = 3;
$interval2 = 7;
$interval3 = 0;

$isfirstlast = ($maxpgno > $maxpages);
$isprevnext = ($maxpgno > 1);
$ndisppages = $curendpgno - $curbeginpgno + 1;

// 총 넓이 계산
$listwidth = $ndisppages * ($imgwidth2 + $interval3) - $interval3;
if ($isfirstlast) {
  $listwidth += ($imgwidth1 * 4 + ($interval1 + $interval2) * 2);
}
else if ($isprevnext) {
  $listwidth += (($imgwidth1 + $interval2) * 2);
}

$x = (int)((945 - $listwidth) / 2);
if ($isfirstlast) {
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/movefirst.png" alt="처음" onclick="goPage(1);"/>';
  $x += ($imgwidth1 + $interval1);
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/moveprev.png" alt="이전" onclick="goPage('.($curpage > 1 ? ($curpage-1) : 1).');"/>';
  $x += ($imgwidth1 + $interval2);
}
else if ($isprevnext) {
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/moveprev.png" alt="이전" onclick="goPage('.($curpage > 1 ? ($curpage-1) : 1).');"/>';
  $x += ($imgwidth1 + $interval2);
}

for ($i = $curbeginpgno ; $i <= $curendpgno ; $i++, $x += ($imgwidth2 + $interval3)) {
    echo '<div style="position:absolute; left:'.$x.'px; top:0px; width:32px; height:31px; cursor:pointer; z-index:9999" onclick="goPage('.$i.');"><image src="image/'.(($curpage == $i) ? "pagenumbgsel" : "pagenumbg").'.png" alt=""/>';
    echo '<lable style="position:relative; left:0px; top:-25px; width:100%; text-align:center;">'.$i.'</label></div>';
}
$x += ($interval2 - $interval3);

if ($isfirstlast) {
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/movenext.png" alt="다음" onclick="goPage('.($curpage < $maxpgno ? ($curpage+1) : $maxpgno).');"/>';
  $x += ($imgwidth1 + $interval1);
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/movelast.png" alt="맨끝" onclick="goPage('.$maxpgno.');"/>';
}
else if ($isprevnext) {
  echo '<image style="position:absolute; left:'.$x.'px; top:0px; cursor:pointer;" src="image/movenext.png" alt="다음" onclick="goPage('.($curpage < $maxpgno ? ($curpage+1) : $maxpgno).');"/>';
}
?>
</div>
</form>
