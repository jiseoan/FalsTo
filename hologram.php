<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

?>
<?php include 'hologram/json.php'; ?>
<?php
$base_URL = 'http://'.(($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['HTTP_HOST']);
$base_URL .= "/hologram/";

$outstr = getJsondata($base_URL);
echo $outstr;
?>
