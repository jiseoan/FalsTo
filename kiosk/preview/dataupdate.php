<?PHP
$rankingoutfile = "../json/ranking.json";
$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';

date_default_timezone_set('Asia/Seoul');
$ftime = filemtime($rankingoutfile);
$releaseno2 = date("YmdHis", $ftime);
?>
{
	"releaseno":"-1",
	"ranking":"<? echo $releaseno2; ?>"
}
