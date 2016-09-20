<?php
include "admin_app_top.php";
if($_SESSION['chklogin']=="")
header("Location:index.php"); 
include "defsAdmin.php"; 
$msg = $_GET['msg'];

// AJAX Stuff ///////////////////////////////

if (isset($_POST['superID'])) {
	$status_query=sprintf("SELECT * FROM %s WHERE %s ='%s'",ITEM_TABLE,ITEM_ID,mysql_real_escape_string($_POST['superID']));
	//echo $status_query;
	$status_set = mysql_query($status_query) or die(mysql_error());
	$row_status_set = mysql_fetch_assoc($status_set);
	if ($row_status_set['super']) {
		$newSuper = 0;
	} else {
		$newSuper = 1;
	}
	
	$query = sprintf("UPDATE %s SET super = '%s' WHERE %s ='%s'",ITEM_TABLE,$newSuper,ITEM_ID,mysql_real_escape_string($_POST['superID']));	
	mysql_query($query);
	echo mysql_error();
	exit;
}

if (isset($_POST['contentID'])) {
	$status_query=sprintf("SELECT * FROM %s WHERE %s ='%s'",ITEM_TABLE,ITEM_ID,mysql_real_escape_string($_POST['contentID']));
	//echo $status_query;
	$status_set = mysql_query($status_query) or die(mysql_error());
	$row_status_set = mysql_fetch_assoc($status_set);
	if ($row_status_set['content']) {
		$newSuper = 0;
	} else {
		$newSuper = 1;
	}
	
	$query = sprintf("UPDATE %s SET content = '%s' WHERE %s ='%s'",ITEM_TABLE,$newSuper,ITEM_ID,mysql_real_escape_string($_POST['contentID']));	
	mysql_query($query);
	echo mysql_error();
	exit;
}

if (isset($_POST['scoresID'])) {
	$status_query=sprintf("SELECT * FROM %s WHERE %s ='%s'",ITEM_TABLE,ITEM_ID,mysql_real_escape_string($_POST['scoresID']));
	//echo $status_query;
	$status_set = mysql_query($status_query) or die(mysql_error());
	$row_status_set = mysql_fetch_assoc($status_set);
	if ($row_status_set['scores']) {
		$newSuper = 0;
	} else {
		$newSuper = 1;
	}
	
	$query = sprintf("UPDATE %s SET scores = '%s' WHERE %s ='%s'",ITEM_TABLE,$newSuper,ITEM_ID,mysql_real_escape_string($_POST['scoresID']));	
	mysql_query($query);
	echo mysql_error();
	exit;
}

// end ajax stuff ///////////////////////////

if (isset($_GET['function']) and $_GET['function'] == 'delete') {
	
	// delete entry

	$query = sprintf("DELETE FROM %s WHERE `%s` ='%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);	
	mysql_query($query);
	echo mysql_error();
	
	header("Location: manage".ITEM_NAME_PLURAL.".php?msg=del");
	exit;
}

$item_query=sprintf("SELECT * FROM %s ",ITEM_TABLE);
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);
$num_items = mysql_num_rows($item_set);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - Manage <?=ITEM_NAME_PLURAL?></title>

<script language="javascript">
<!--
function sureitemDelete(cid,nm)
{
	var getConfirm;
	getConfirm = confirm("Are you sure you want to delete this <?=ITEM_NAME?>?")
	if (getConfirm == true)
	{
		document.location.href = "manage<?=ITEM_NAME_PLURAL?>.php?function=delete&<?=ITEM_ID?>="+ cid 
	}

}
-->
</script>
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
                      <td width="4%">&nbsp;</td>
                      <td width="90%"><TABLE border=0 align="left" cellPadding=3 cellSpacing=0 class=body>
                    		<TR> 
                  			<TD><a href="admin_page.php" class="adminLink">Return to Admin</a></TD>
                  			<TD>|</TD>
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="6%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableHeading" align="center"><div id="manageHeading">Manage <?=ITEM_NAME_PLURAL?> </div>                        <div id="addLink" align="right"><a href="edit<?=ITEM_NAME?>.php?f=add"><span class="addSign">+</span>&nbsp;&nbsp;Add <?=ITEM_NAME?></a></div></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="alert">
					  <?php if ($_GET['msg'] == 'del') { echo ITEM_NAME." deleted successfully."; 
							} elseif ($_GET['msg'] == 'new') { echo ITEM_NAME." added successfully.";
							} elseif ($_GET['msg'] == 'up') { echo ITEM_NAME." updated successfully.";
							}?></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="center" valign="top"><?php if ($num_items > 0) { ?>
		<table width="50%" border="0" cellpadding="5" cellspacing="2">
	 <tr>
	   <td class="adminTableItemHeading" width="37%" align="left">Username</td>
	   <td width="11%" class="adminTableItemHeading"  align="center">Super</td>
	   <td width="11%" class="adminTableItemHeading"  align="center">Content</td>
	   <td width="10%" class="adminTableItemHeading"  align="center">Scores</td>
	   <!--<td width="35%" class="adminTableItemHeading"  align="left">Admin Type</td>-->
	   <td width="22%" class="adminTableItemHeading"  align="center">Change Password</td>
	   <td width="9%" class="adminTableItemHeading" align="center">Delete </td>
        </tr>
	<?php 
	$color_flag = 0;
	
	do { 
	?>
	
	<tr <?php if ($color_flag == 1) { echo ('class = "background_lite"'); } else { echo ('class = "background_dark"'); } ?>>
	  <td align="left" valign="top"  class="tableContent" ><?=stripslashes($row_item_set['username'])?></td>
	  <td align="center" valign="top"><input type="checkbox" name="super" id="super" onChange="changeSuper(<?=$row_item_set[ITEM_ID]?>);" <?php if ($row_item_set['super']) echo "checked"?>></td>
	  <td align="center" valign="top"><input type="checkbox" name="content" id="content" onChange="changeContent(<?=$row_item_set[ITEM_ID]?>);" <?php if ($row_item_set['content']) echo "checked"?>></td>
	  <td align="center" valign="top"><input type="checkbox" name="scores" id="scores" onChange="changeScores(<?=$row_item_set[ITEM_ID]?>);" <?php if ($row_item_set['scores']) echo "checked"?>></td>
	  <!--<td align="left" class="tableContent"><?=stripslashes($row_item_set['adminType'])?></td>-->
	  <td align="center" valign="top"><a href="editPassword.php?<?=ITEM_ID?>=<?=$row_item_set[ITEM_ID]?>"><img src="images/write.gif" border="0"></a></td>
	  <td  class="header_text_16px" align="center" valign="top"><a href="javascript: sureitemDelete('<?=$row_item_set[ITEM_ID]?>')"><img src="images/delete_icon.gif"  border="0"/></a></td>
	    </tr>
      
	  <?php 
	  if ($color_flag == 1) {
	  	$color_flag = 0;
	  } else {
		$color_flag = 1;
	  }
	  $idx++;
		} while ($row_item_set = mysql_fetch_assoc($item_set)); ?>
    </table>
		<?php }?></td>
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
function changeSuper(id)
{
$.post( "manageAdmins.php", { superID: id} );
}

function changeContent(id)
{
$.post( "manageAdmins.php", { contentID: id} );
}

function changeScores(id)
{
$.post( "manageAdmins.php", { scoresID: id} );
}
-->
</script>
</body>
</html>