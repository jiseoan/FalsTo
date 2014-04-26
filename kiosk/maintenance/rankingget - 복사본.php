<?php
function rankingGet($conn, $date, $floor, $category) {
  $arr = array();
  $str = "SELECT DISP_TITLE, RANKING, BRAND_NM, GOODS_NM, RANKING_GB, STEP FROM MDVIEWER WHERE SALDATE = '".$date."' AND STORE_CD = '009100' AND INFO_GB = '2' AND FLOOR_CD = '".$floor."' AND CATEGORY_CD = '".$category."' AND RANKING <= 10 ORDER BY RANKING";
  $stid = oci_parse($conn, $str);
  if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    return $arr;
  }

  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    return $arr;
  }
  
  for ($i = 0 ; ($row = oci_fetch_assoc($stid)) != false ; $i++) {
    if ($row['RANKING_GB'] == '0' || $row['RANKING_GB'] == 'N') {
      $arr[$i] = array("disptitle"=>$row['DISP_TITLE'], "ranking"=>$row['RANKING'], "brand"=>$row['BRAND_NM'], "product"=>$row['GOODS_NM'], "change"=>$row['RANKING_GB']);
    }
    else {
      $arr[$i] = array("disptitle"=>$row['DISP_TITLE'], "ranking"=>$row['RANKING'], "brand"=>$row['BRAND_NM'], "product"=>$row['GOODS_NM'], "change"=>$row['RANKING_GB'].$row['STEP']);
    }
  }
  
  oci_free_statement($stid);
  return $arr;
}
?>
<?php
$compath = '/common/';
if (isset($_SERVER["DOCUMENT_ROOT"]) && strlen($_SERVER["DOCUMENT_ROOT"]) > 0) {
  $compath = '..'.$compath;
}
else {
  $compath = dirname(dirname(__FILE__))."/".$compath;
}

include $compath.'db.php';
?>
<?php
$rnkdbip = "127.0.0.1";
$rnkdbport = 3306;
$user = "MDVIEWER";
$pwd = "MDVIEWER_9309";

$ok = $db->open();
if ($ok) {
	$str = "SELECT rankingdbip, rankingdbport FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$rnkdbip = $row['rankingdbip'];
		$rnkdbport = $row['rankingdbport'];
	}
	$db->free();
	$db->close();
}

$constr = $rnkdbip.":".$rnkdbport."/DEPT";
$rankingoutfile = $web_dir."/json/ranking.json";

echo $constr."<br/>\r\n";
echo $rankingoutfile."<br/>\r\n";

$qrylist = array( array("B1F", "푸드코트", "WB1F", "003"),
                  array("B1F", "푸드코트(아이템)", "WB1F", "004"),
                  array("1F", "화장품", "W1F", "007"),
                  array("1F", "직영MD", "W1F", "008"),
                  array("2F", "의류", "W2F", "001"),
                  array("2F", "직영MD", "W2F", "008"),
                  array("3F", "의류", "W3F", "001"),
                  array("3F", "슈즈", "W3F", "002"),
                  array("3F", "데님", "W3F", "005"),
                  array("3F", "직영MD", "W3F", "008"),
                  array("4F", "의류", "W4F", "001"),
                  array("4F", "슈즈", "W4F", "002"),
                  array("4F", "데님", "W4F", "005"),
                  array("4F", "직영MD", "W4F", "008"),
                  array("5F", "기프트 멀티", "W5F", "006"),
                  array("5F", "Life 스타일", "W5F", "009"),
                  array("5F", "직영MD", "W5F", "008"));
$nqrylist = count($qrylist);
$arr = array();

date_default_timezone_set('Asia/Seoul');

$conn = oci_connect($user, $pwd, $constr, "utf8");
if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  exit;
}

$date = date("Y-m-d");

for ($i = 0 ; $i < $nqrylist ; $i++) {
  $list = rankingGet($conn, $date, $qrylist[$i][2], $qrylist[$i][3]);
  $arr[$i] = array("floor"=>$qrylist[$i][0], "category"=>$qrylist[$i][1], "list"=>$list);
}

oci_close($conn);

$outarr = array("tables"=>$arr);
$outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

echo $outstr;

$fd = fopen($rankingoutfile, 'w');
fwrite($fd, $outstr);
fclose($fd);

?>
