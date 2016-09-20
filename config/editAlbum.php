<?php
include "admin_app_top.php";
if($_SESSION['chklogin']=="")
header("Location:index.php"); 
include "defsAlbum.php"; 

$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);

if ($_POST['submit']) {
	if ($_GET['function'] == "update") {

		$query = sprintf("UPDATE %s SET albumTitle = '%s', albumDescription = '%s' WHERE `%s` ='%s'",ITEM_TABLE,addslashes($_POST['albumTitle']),addslashes($_POST['albumDescription']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
	
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
	
		$list_query=sprintf("SELECT * FROM `%s`",ITEM_TABLE);
		$list_set = mysql_query($list_query) or die(mysql_error());
		$listOrder = mysql_num_rows($list_set) + 1;
	
		$query = sprintf("INSERT INTO %s (albumTitle,albumDescription,listOrder) VALUES('%s','%s','%s')",ITEM_TABLE,addslashes($_POST['albumTitle']),addslashes($_POST['albumDescription']),$listOrder);	
		mysql_query($query);
		echo mysql_error();
		$albumID = mysql_insert_id();
		
		mkdir ('photos//'.$albumID);
		
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=new");
		exit;
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - <?php if ($_GET['f'] == 'add') { ?>Add<?php } else {?>Edit<?php }?>&nbsp;<?=ITEM_NAME?></title>
<script type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../scripts/tinyInit.js"></script>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top"><? include("header.inc.php"); ?> </td>
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
                      <td valign="top" class="tableHeading" align="center"><?php if ($_GET['f'] == 'add') { ?>Add<?php } else {?>Edit<?php }?>&nbsp;<?=ITEM_NAME_ENGLISH?>  </td>
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
      <td align="right" valign="top" class="adminTableItemHeading">Album Title</td>
      <td ><input name="albumTitle" type="text" class="input_text" size="80" value="<?=stripslashes($row_item_set['albumTitle'])?>"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Album Description</td>
      <td ><textarea name="albumDescription" cols="40" rows="5"><?=stripslashes($row_item_set['albumDescription'])?></textarea></td>
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
function checkForm()
{
   var d=document.form1;
   
	if (d.clubName.value == '') {
		alert('Please select a club');
		d.clubName.focus();
		return false;
	}
}
  -->
</script>