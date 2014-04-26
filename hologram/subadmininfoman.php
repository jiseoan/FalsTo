<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>
<?php include './common/manlang.php'; ?>
<?php include './common/db.php'; ?>
<?php
$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$idadmin = $_SESSION['userid'];
$manLang = $_SESSION['manLang'];
$manLangCode = $_SESSION['manLangCode'];
$email = "";
$langs = array();

$str = "select email from t_admin where idadmin = '".$idadmin."';";

$ok = $db->open();

if ($ok) {
	$n = $db->querySelect($str);
	if ($n > 0) {
		$row = $db->goNext();
		$email = $row['email'];
	}
	$db->free();

	$str = "SELECT idlang, name FROM t_lang;";
	$n = $db->querySelect($str);
	for ($i = 0 ; $i < $n ; $i++) {
		$langs[$i] = array();
		$row = $db->goNext();
		$langs[$i][0] = $row['idlang'];
		$langs[$i][1] = $row['name'];
	}
	$db->free();

	$db->close();
}
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript">
function onPwdTest() {
    if (regForm.pwd.value.length < 1) {
    	alert("<? echo $langRes["message"][0][$manLangCode] ?>.");
    	regForm.pwd.focus();
    	return false;
    }
    
    if (regForm.pwdnew.value.length < 1) {
    	alert("<? echo $langRes["message"][1][$manLangCode] ?>.");
    	regForm.pwdnew.focus();
    	return false;
    }

    if (regForm.pwd.value != regForm.pwdnew.value) {
    	alert("<? echo $langRes["message"][2][$manLangCode] ?>.");
    	regForm.pwdnew.focus();
    	return false;
    }
    
	regForm.acttype.value = "pwd";
    regForm.submit();
    return true;
}

function onEmailTest() {
    if (regForm.email.value.length < 3) {
    	alert("<? echo $langRes["message"][3][$manLangCode] ?>.");
    	regForm.email.focus();
    	return false;
    }

    if (regForm.email.value.indexOf("@") < 1) {
    	alert("<? echo $langRes["message"][4][$manLangCode] ?>.");
    	regForm.email.focus();
    	return false;
    }
    	
    if (regForm.emailnew.value.length < 3) {
    	alert("<? echo $langRes["message"][5][$manLangCode] ?>.");
    	regForm.emailnew.focus();
    	return false;
    }

    if (regForm.email.value != regForm.emailnew.value) {
    	alert("<? echo $langRes["message"][6][$manLangCode] ?>.");
    	regForm.emailnew.focus();
    	return false;
    }
    
	regForm.acttype.value = "email";
    regForm.submit();
    return true;
}
</script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">


<form id="regForm" name="regForm" method="post" action="./subadmininfoupdate.php">
<input type="hidden" name="acttype" value=""/>
<table style="position:relative; left:0px; top:15px; width:945px;" class="tblnoborder">
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][0][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder">admin</td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][1][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="password" name="pwd"  maxlength="60" style="border-style:none; text-align:center;" value=""/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][2][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="password" name="pwdnew"  maxlength="60" style="position:relative; top:-7px; border-style:none; text-align:center;" value=""/>
<image src="./image/<? echo $manLangCode; ?>/change.png" style="position:relative; left:20px; top:5px;width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="onPwdTest();"/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][3][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="text" name="email"  maxlength="80" style="border-style:none; text-align:center;" value="<? echo $email; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][4][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="text" name="emailnew"  maxlength="80" style="position:relative; top:-7px; border-style:none; text-align:center;" value=""/>
<image src="./image/<? echo $manLangCode; ?>/change.png" style="position:relative; left:20px; top:5px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="onEmailTest();"/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder"><? echo $langRes["label"][5][$manLangCode] ?></td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><select name="manlang"  style="position:relative; top:-7px;">
<?php
for ($i = 0, $n = count($langs) ; $i < $n ; $i++) {
	echo '<option value="'.$langs[$i][0].'"'.($langs[$i][0] == $manLang ? "selected" : "").'>'.$langs[$i][1].'</option>';
}
?>
</select><image src="./image/<? echo $manLangCode; ?>/change.png" style="position:relative; left:20px; top:5px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.acttype.value = 'manlang'; regForm.submit();"/></td>
</tr>
</table>
</form>


</body>
</html>
