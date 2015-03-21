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

if ($ok) {
  $str = "SELECT ifnull(max(idgrp), 0) as maxid FROM t_grp where idgrpcateg = 1;";
	$maxid = $db->queryCount($str, "maxid");

	if ($maxid == null) {
		$ok = false;
	}
  else {
    $str = "SELECT ifnull(max(seq), 0) as maxseq FROM t_grp where idgrpcateg = 1;";
	  $maxseq = $db->queryCount($str, "maxseq");
    
	  $str = "INSERT INTO t_grp (idgrp, idgrpcateg, seq, idlang, name) VALUES (".($maxid+1).", 1, ".($maxseq == null ? 1 : ($maxseq+1)).", 1, '".$langRes["label"][0][$manLangCode]."');";
	  if ($db->query($str) == 0) {
		  $ok = false;
	  }
  }

	$db->close();
}
?>

<html>
<body>

  <form id="upForm" name="upForm" method="post" action="./subcategman.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
</form>
<?php
echo "<script>";
if (!$ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
  echo "upForm.submit();";
echo "</script>";
?>
  </body>
</html>
