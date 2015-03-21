<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

if (!isset($_GET['appid']) || strlen($_GET['appid']) <= 0) {
echo "error appid";
exit;
}

$appid = $_GET['appid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>GalleriaKiosk</title>
	<meta name="description" content="" />
	
	<script src="js/swfobject.js"></script>
	<script>
		var flashvars = {
			appid: "<? echo $appid; ?>"
		};
		var params = {
			menu: "false",
			scale: "showAll",
			allowFullscreen: "true",
			allowScriptAccess: "always",
			bgcolor: "",
			wmode: "direct" // can cause issues with FP settings & webcam
		};
		var attributes = {
			id:"GalleriaKiosk"
		};
		swfobject.embedSWF(
			"GalleriaKiosk.swf", 
			"altContent", "100%", "100%", "10.0.0", 
			"expressInstall.swf", 
			flashvars, params, attributes);
	</script>
	<style>
		html, body { height:100%; overflow:hidden; }
		body { margin:0; }
	</style>
</head>
<body>
	<div id="altContent">
		<h1>GalleriaKiosk</h1>
		<p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
	</div>
</body>
</html>