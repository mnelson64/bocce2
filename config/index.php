<?php 
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
include "admin_app_top.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=SITE_NAME?> Site Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="admin_styles.css" rel="stylesheet" type="text/css">
<?php include "../include/jquery.php";?>
</head>
<body> 
<table width="900" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
      <td ><?php include("header.inc.php"); ?></td>
  </tr>
  <tr>
   
    <td width="51%" height="129" align="center" valign="middle"><span class="alert">This is a restricted access area intended for the owners and administrators of the site only.<br> 
    Enter your login information if you have been granted access or click <a href="../index.php">here</a> to return to the Home Page.</span></td>
    
  </tr>
  <tr>
      
    <td height="318"  align="center" valign="top" ><table width="209"  border="0"  cellpadding="4" cellspacing="0" >
	<form action="checkLogin.php" method="post" name="index_form" onSubmit="return checkForm()">
        <tr align="center" bgcolor="#F15A25"> 
            <td colspan="2"  class="tableHeading"><div align="center">Login</div></td>
        </tr>
        <tr> 
          <td colspan="2" align="center"><span class="red_alert"><? if($_GET['msg']==2) echo "Login failed. Please try again.";?></span>          
           </td>
        </tr>
        <tr> 
          <td width="88" align="right"><strong>Username:</strong></td>
          <td width="137" align="left"><input name="email" type="text"  id="username" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)">          </td>
        </tr>
        <tr> 
          <td align="right"><strong>Password:</strong></td>
          <td align="left"><input name="password" type="password"  id="password" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)">          </td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <TD><input name="button1" type="submit"   value="Login" ></TD>
        </tr>
        <tr align="center"> 
            <td height="21" colspan="2">&nbsp;</td>
        </tr></form>
    </table>    </td>
  </tr>
  <tr>
    <td height="20"  valign="bottom"><?php include("down.inc.php"); ?>    </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript">
<!--
function checkForm() {
	if($("#username").val() == '' ) {
		alert('Please enter your user name');
		$("#username").addClass('hilightError');
		$("#username").focus();
		return false;	
	}
	if($("#password").val() == '' ) {
		alert("Please enter your password")
		$("#password").addClass('hilightError');
		$("#password").focus();
		return false
	}
	
}

function clearError(id) {
	$("#"+id).removeClass('hilightError');
	return false;	
	
}
-->
</script>