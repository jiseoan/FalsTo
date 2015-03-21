<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$grpname = $_POST["grpname"];
$idlang = $_POST["idlang"];
$idgrp = $_POST["idgrp"];
$seq = $_POST["seq"];
?>

<?php include './common/db.php'; ?>
<?php
$nnames = count($grpname);
$nidlangs = count($idlang);
$nidgrps = count($idgrp);

$ok = $db->open();

if ($ok) {
  for ($i = 0, $k = 0 ; $i < $nidgrps ; $i++) {
    for ($j = 0 ; $j < $nidlangs ; $j++, $k++) {
			$str = "update t_grp set seq = ".$seq[$i].", name = '".addslashes($grpname[$k])."' where idgrp = ".$idgrp[$i]." and idlang = ".$idlang[$j].";";
			$n = $db->query($str);
      if ($n <= 0) {
    	  $str = "INSERT INTO t_grp (idgrp, idgrpcateg, seq, idlang, name) VALUES (".$idgrp[$i].", 1, ".$seq[$i].", ".$idlang[$j].", '".addslashes($grpname[$k])."');";
        $db->query($str);
      }
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
if ($ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
else {
	echo "alert('".$langRes["message"][1][$manLangCode]."');";
}
echo "upForm.submit();";
echo "</script>";
?>
  </body>
</html>
