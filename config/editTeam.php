<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsTeam.php"; 

// AJAX 
// end AJAX
$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $item_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);

if ($_POST['submit']) {
	if ($_GET['function'] == "update") {
					
		$query = sprintf("UPDATE %s SET `teamYear` = '%s',`teamNight` = '%s',`teamName` = '%s',`teamCapo` = '%s' WHERE `%s` = %s",ITEM_TABLE,mysql_real_escape_string($_POST['teamYear']),mysql_real_escape_string($_POST['teamNight']),mysql_real_escape_string($_POST['teamName']),mysql_real_escape_string($_POST['teamCapo']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		//exit;
		mysql_query($query);
		echo mysql_error();
		
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
		
		
		$query = sprintf("INSERT INTO %s (teamYear,teamNight,teamName,teamCapo) VALUES('%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['teamYear']),mysql_real_escape_string($_POST['teamNight']),mysql_real_escape_string($_POST['teamName']),mysql_real_escape_string($_POST['teamCapo']));	
		//echo $query;
		//exit;
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
<link href="admin_styles.css" rel="stylesheet" type="text/css">
<?php //include "../include/jquery.php";?>
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top"><? include("header.inc.php"); ?> </td>
  </tr>
  <tr>   
     <td  colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="4%">&nbsp;</td>
                      <td width="90%"><TABLE border=0 align="left" cellPadding=3 cellSpacing=0 class=body>
                    		<TR> 
                  			<TD><a href="admin_page.php" class="adminLink">Return to Admin</a></TD>
                            <TD>|</TD>
                            <TD><a href="manage<?=ITEM_NAME_PLURAL?>.php" class="adminLink">Manage <?=ITEM_NAME?>s</a></TD>
                  			<TD>|</TD>
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="6%">&nbsp;</td>
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
      <td width="16%" align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td width="84%" class="alert">&nbsp;</td>
    </tr>
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Year</td>
      <td><select name="teamYear" id="teamYear">
        <option value="">Select</option>      
        <?php 
		  for ($y=2012; $y <= (date('Y')+2); $y++) { ?>
			<option <?php if ($row_item_set['teamYear'] == $y or ($row_item_set['teamYear'] == '' and $_SESSION['sTeamYear'] == $y)) echo 'selected="selected"';?>><?=$y?></option>
		  <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Team Name</td>
      <td ><input name="teamName" id="teamName" type="text" class="input_text" size="50" value="<?=$row_item_set['teamName']?>"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Night</td>
      <td ><select name="teamNight" id="teamNight">
        <option value="" <?php if ($teamNight == '') echo 'selected="selected"';?>>Select Night</option>
        <?php foreach ($nightArray as $night) {?>
        <option value="<?=$night?>" <?php if ($row_item_set['teamNight'] == $night or ($row_item_set['teamNight'] == '' and $_SESSION['sTeamNight'] == $night)) echo 'selected="selected"';?>><?=$night?></option>
        <?php } ?>
        </select>
        </td>
    </tr>
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Capo</td>
      <td ><input name="teamCapo" id="teamCapo" type="text" class="input_text" size="50" value="<?=$row_item_set['teamCapo']?>"></td>
    </tr>
    <?php if ($_GET['f'] == 'add') { ?>
    <?php } ?>
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
<script type="text/javascript">
<!--
function checkForm() {
	if($("#regFirstName").val() == '' ) {
		alert("Please enter your first name")
		$("#regFirstName").addClass('hilightError');
		$("#regFirstName").focus();
		return false
	}
	if($("#regLastName").val() == '' ) {
		alert("Please enter your last name")
		$("#regLastName").addClass('hilightError');
		$("#regLastName").focus();
		return false
	}
	if($("#regAddress1").val() == '' ) {
		alert("Please enter your address")
		$("#regAddress1").addClass('hilightError');
		$("#regAddress1").focus();
		return false
	}
	if($("#regCity").val() == '' ) {
		alert("Please enter your city")
		$("#regCity").addClass('hilightError');
		$("#regCity").focus();
		return false
	}
	if($("#regState").val() == '' ) {
		alert("Please enter your state, province or region")
		$("#regState").addClass('hilightError');
		$("#regState").focus();
		return false
	}
	if($("#regZip").val() == '' ) {
		alert("Please enter your ZIP or postal code")
		$("#regZip").addClass('hilightError');
		$("#regZip").focus();
		return false
	}
	if($("#regCountry").val() == '' ) {
		alert("Please enter your country")
		$("#regCountry").addClass('hilightError');
		$("#regCountry").focus();
		return false
	}
	if($("#regPhone").val() == '' ) {
		alert("Please enter your phone number")
		$("#regPhone").addClass('hilightError');
		$("#regPhone").focus();
		return false
	}
	if($("#regEmail").val() == '' ) {
		alert("Please enter your email address")
		$("#regEmail").addClass('hilightError');
		$("#regEmail").focus();
		return false;
	}
	var str=$("#regEmail").val();
	var AtTheRate= str.indexOf("@");
	var DotSap= str.lastIndexOf(".");
	if (AtTheRate==-1 || DotSap ==-1)
	{
		alert("Please enter a valid email address");
		$("#regEmail").addClass('hilightError');
		$("#regEmail").focus();
		return false;
	}
	else
	{
		if( AtTheRate > DotSap )
		{
		alert("Please enter a valid email address");
		$("#regEmail").addClass('hilightError');
		$("#regEmail").focus();
		return false;
		}
	}
	if($("#regPassword").val() == '' ) {
        alert("Please enter a password")
        $("#regPassword").addClass('hilightError');
        $("#regPassword").focus();
        return false
    }
    if($("#regPassword").val().length < 8){
        alert('Password must contain at least 8 characters');
        $("#regPassword").addClass('hilightError');
        $("#regPassword").focus();
        return false;
    }
    if(hasNoUppercase($("#regPassword").val())){
        alert('Password must contain at least one uppercase character');
        $("#regPassword").addClass('hilightError');
        $("#regPassword").focus();
        return false;
    }
    if(hasNoNumber($("#regPassword").val())){
        alert('Password must contain at least one number');
        $("#regPassword").addClass('hilightError');
        $("#regPassword").focus();
        return false;
    }
    if($("#regPassword").val()!= $("#regPassword1").val())
    {
        alert("The passwords entered don't match, please try again")
        $("#regPassword").addClass('hilightError');
        $("#regPassword").focus();
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
</body>
</html>
