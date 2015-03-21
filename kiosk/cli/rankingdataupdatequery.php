<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? $_POST["cliip"] : '';
$releaseno = isset($_POST["releaseno"]) ? $_POST["releaseno"] : '';

$rankingoutfile = "json/ranking.json";

date_default_timezone_set('Asia/Seoul');
$ftime = filemtime("../".$rankingoutfile);
$releaseno2 = date("YmdHis", $ftime);


echo ($releaseno == $releaseno2 ? "NOT" : "OK")."\r\n";
echo $releaseno2."\r\n";
echo $rankingoutfile."\r\n";
?>
