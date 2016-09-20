<?php
include "admin_app_top.php";
if($_SESSION['chklogin']=="")
header("Location:index.php"); 
include "defsAdmin.php"; 

if ($_POST['submit']) {
	
	$query = sprintf("UPDATE %s SET password = '%s' WHERE `%s` ='%s'",ITEM_TABLE,mysql_real_escape_string(md5($_POST['userPassword'])),ITEM_ID,$_POST[ITEM_ID]);	
	//echo $query;
	mysql_query($query);
	echo mysql_error();
	header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
	exit;
		
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - Change Password</title>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
<?php include "../include/jquery.php";?>
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top"><?php include("header.inc.php"); ?> </td>
  </tr>
  <tr>   
     <td  colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1%">&nbsp;</td>
                      <td width="98%"><TABLE border=0 align="left" cellPadding=3 cellSpacing=0 class=body>
                    		<TR>
                            <?php if ($_SESSION['adminType'] == 'Superuser') {?> 
                  			<TD><a href="admin_page.php" class="adminLink">Return to Admin</a></TD>
                            <TD>|</TD>
                            <?php } ?>
                            <TD><a href="manage<?=ITEM_NAME_PLURAL?>.php" class="adminLink">Manage <?=ITEM_NAME?>s</a></TD>
                  			<TD>|</TD>
                            
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="1%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableHeading" align="center">Change Password</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
					  <td valign="top">
                        <form action="editPassword.php?function=update" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkForm();">
                        <input name="<?=ITEM_ID?>" type="hidden" value="<?=$_GET[ITEM_ID]?>">
                            
                                               
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                            <td width="15%">&nbsp;</td>
                              <td width="85%" valign="top" class="alert" id="messageArea" >
                              <?php if ($_GET['msg'] == 'del') { echo "Account deleted successfully."; 
                                    } elseif ($_GET['msg'] == 'new') { echo "Account added successfully.";
                                    } elseif ($_GET['msg'] == 'up') { echo "Account updated successfully.";
                                    }?>&nbsp;</td>
                            </tr>
                             <tr>
                              <td align="right" valign="top" class="adminTableItemHeading">Password</td>
                              <td ><input name="userPassword" id="userPassword" type="password" class="input_text" size="40" value="" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)"></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="adminTableItemHeading">Verify Password</td>
                              <td ><input name="userPassword2" id="userPassword2" type="password" class="input_text" size="40" value=""></td>
                            </tr>
                            
                             <tr>
                               <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
                               <td>&nbsp;</td>
                             </tr>
                            
                            <tr>
                              <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
                              <td ><input name="submit" type="submit" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;<input name="back" type="submit" value="Back" onClick="form1.action='manage<?=ITEM_NAME_PLURAL?>.php'; form1.onsubmit=function(){};return true;"></td>
                            </tr>
                            </table>
                         </form>
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>

    </td>
  </tr>
 
        
      </table></td>
  </tr>
  
  <tr> 
    <td valign="bottom">
      <?php include("down.inc.php"); ?>    </td>
  </tr>
</table>
</body>
</html>
 <script type="text/javascript">
<!--
function checkForm() {
    if($("#userPassword").val() == '' ) {
        alert("Please enter a password")
        $("#userPassword").addClass('hilightError');
        $("#userPassword").focus();
        return false
    }
    if($("#userPassword").val().length < 8){
        alert('Password must contain at least 8 characters');
        $("#userPassword").addClass('hilightError');
        $("#userPassword").focus();
        return false;
    }
    if(hasNoUppercase($("#userPassword").val())){
        alert('Password must contain at least one uppercase character');
        $("#userPassword").addClass('hilightError');
        $("#userPassword").focus();
        return false;
    }
    if(hasNoNumber($("#userPassword").val())){
        alert('Password must contain at least one number');
        $("#userPassword").addClass('hilightError');
        $("#userPassword").focus();
        return false;
    }
    if($("#userPassword").val()!= $("#userPassword2").val())
    {
        alert("The passwords entered don't match, please try again")
        $("#userPassword").addClass('hilightError');
        $("#userPassword").focus();
        return false
    }
    
}

function clearError(id) {
    $("#"+id).removeClass('hilightError');
    return false;	
    
}
function hasNoUppercase(elem){
    var alphaExp = /[A-Z]/;
    if(elem.match(alphaExp)){
        return false;
    } else {
        return true;
    }
}
function hasNoNumber(elem){
    var alphaExp = /[0-9]/;
    if(elem.match(alphaExp)){
        return false;
    } else {
        return true;
    }
}
-->
</script>
