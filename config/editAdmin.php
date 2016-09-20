<?php
include "admin_app_top.php";
if($_SESSION['chklogin']=="")
header("Location:index.php"); 
include "defsAdmin.php"; 

$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);

if ($_POST['submit']) {
	if ($_GET['function'] == "update") {
		
		$query = sprintf("UPDATE %s SET username = '%s',password = '%s' WHERE `%s` ='%s'",ITEM_TABLE,mysql_real_escape_string($_POST['username']),mysql_real_escape_string(md5($_POST['userPassword'])),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();

		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
				
		$query = sprintf("INSERT INTO %s (username,password) VALUES('%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['username']),mysql_real_escape_string(md5($_POST['userPassword'])));	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
		
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=new");
		exit;
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - <?php if ($_GET['f'] == 'add') { ?>Add<?php } else {?>Edit<?php }?>&nbsp;<?=ITEM_NAME?></title>
<?php //include "../jquery.php";?>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
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
                      <td valign="top" class="tableHeading" align="center"><?php if ($_GET['f'] == 'add') { ?>Add<?php } else {?>Edit<?php }?>&nbsp;<?=ITEM_NAME?>  </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
					  <td valign="top">
                      <?php if ($_GET['f'] == 'add') { ?>
                      		<form action="edit<?=ITEM_NAME?>.php?function=add" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkForm();">
                       <?php } else {?>
               		  <form action="edit<?=ITEM_NAME?>.php?function=update" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkForm();">
                            <input name="<?=ITEM_ID?>" type="hidden" value="<?=$_GET[ITEM_ID]?>">
                            
                            
                       <?php }?>
                       
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
    
    <tr>
      <td width="15%" align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Username</td>
      <td ><input name="username" id="username" type="text" class="input_text" size="80" value="<?=stripslashes($row_item_set['username'])?>" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Password</td>
      <td ><input name="userPassword" id="userPassword" type="password" class="input_text" size="80" value="<?=stripslashes($row_item_set['password'])?>" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Verify Password</td>
      <td ><input name="userPassword2" id="userPassword2" type="password" class="input_text" size="80" value="<?=stripslashes($row_item_set['password'])?>"></td>
    </tr>
     <tr>
       <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
       <td >&nbsp;</td>
     </tr>
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td ><input name="submit" type="submit" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;<input name="back" type="submit" value="Back" onClick="form1.action='manage<?=ITEM_NAME_PLURAL?>.php'; form1.onsubmit=function(){};return true;"></td>
    </tr>
    </table>
 
        </form></td>
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