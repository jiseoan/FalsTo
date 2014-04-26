<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$idlang = $_POST["idlang"];
$cons = $_POST["cons"];
?>

<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
<?php
$ok = $db->open();

if ($ok) {
	$n = count($idlang);

	for ($i = 0 ; $i < $n ; $i++)
	{
		// 초성처리
		$db->autocommit(true);
		$str = "update t_lang set consonantseq = '".$cons[$i]."' where idlang = ".$idlang[$i].";";
		$db->query($str);

		// 파일 처리
		if ($_FILES["file"]["error"][$i] > 0) {
			continue;
		}
    
		$prefix = "upimg/_".$idlang[$i];
		$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"][$i], $_FILES["file"]["tmp_name"][$i]);

		if (strlen($dstpath) > 0) {
		  $str = "insert into t_envlang (idlang, menuimage) values (".$idlang[$i].", '".$dstpath."');";
		  if ($db->query($str) <= 0) {
			  $str = "update t_envlang set menuimage = '".$dstpath."' where idlang = ".$idlang[$i].";";
			  $db->query($str);
		  }
    }
		else {
			$ok = false;
		}
	}

	$db->close();
}


echo "<script>";
if ($ok) {
	echo "alert('저장하였습니다');";
}
else {
	echo "alert('일부 혹은 전부를 저장하지못하였습니다');";
}
echo "document.location.replace('./sublangman.php');";
echo "</script>";

?>
