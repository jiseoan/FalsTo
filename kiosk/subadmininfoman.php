<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>
<?php include './common/db.php'; ?>
<?php

$idadmin = $_SESSION['userid'];
$email = "";
$str = "select email from t_admin where idadmin = '".$idadmin."';";

$ok = $db->open();

if ($ok) {
  $n = $db->querySelect($str);
  if ($n > 0) {
	$row = $db->goNext();
	$email = $row['email'];
  }
  $db->free();
  $db->close();
}
?>


<html>
<head>
<title>
관리자정보수정
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript">
function onPwdTest() {
    if (regForm.pwd.value.length < 1) {
    	alert("패스워드를 입력하세요.");
    	regForm.pwd.focus();
    	return false;
    }
    
    if (regForm.pwdnew.value.length < 1) {
    	alert("패스워드를 입력하세요.");
    	regForm.pwdnew.focus();
    	return false;
    }

    if (regForm.pwd.value != regForm.pwdnew.value) {
    	alert("패스워드가 다르게 입력되었습니다.");
    	regForm.pwdnew.focus();
    	return false;
    }
    
	regForm.acttype.value = "pwd";
    regForm.submit();
    return true;
}

function onEmailTest() {
    if (regForm.email.value.length < 3) {
    	alert("이메일을 입력하세요.");
    	regForm.email.focus();
    	return false;
    }

    if (regForm.email.value.indexOf("@") < 1) {
    	alert("올바른 이메일형식이 아닙니다.");
    	regForm.email.focus();
    	return false;
    }
    	
    if (regForm.emailnew.value.length < 3) {
    	alert("이메일을 입력하세요.");
    	regForm.emailnew.focus();
    	return false;
    }

    if (regForm.email.value != regForm.emailnew.value) {
    	alert("이메일이 다르게 입력되었습니다.");
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
<td style="width:100px; text-align:right;" class="tdnoborder">관리자ID</td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder">admin</td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder">관리자PW</td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="password" name="pwd"  maxlength="60" style="border-style:none; text-align:center;" value=""/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder">관리자PW확인</td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="password" name="pwdnew"  maxlength="60" style="position:relative; top:-7px; border-style:none; text-align:center;" value=""/>
<image src="./image/change.png" style="position:relative; left:20px; top:5px;width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="onPwdTest();"/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder">관리자email</td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="text" name="email"  maxlength="80" style="border-style:none; text-align:center;" value="<? echo $email; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:100px; text-align:right;" class="tdnoborder">관리자email확인</td>
<td style="width:845px; text-align:left; padding-left:5px;" class="tdnoborder"><input type="text" name="emailnew"  maxlength="80" style="position:relative; top:-7px; border-style:none; text-align:center;" value=""/>
<image src="./image/change.png" style="position:relative; left:20px; top:5px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="onEmailTest();"/></td>
</tr>
</table>
</form>


</body>
</html>
