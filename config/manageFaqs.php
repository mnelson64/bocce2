<?php
include "admin_app_top.php";
include "checkUser.php";
include "defsFaq.php"; 
$msg = $_GET['msg'];

if (isset($_GET['function']) and $_GET['function'] == 'delete') {
	
	
	$del_query=sprintf("SELECT * FROM %s WHERE `%s` ='%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
	//echo $del_query;
	$del_set = mysql_query($del_query) or die(mysql_error());
	$row_del_set = mysql_fetch_assoc($del_set);
	
	// shuffle photo orders up
	$listOrder = $row_del_set['listOrder'];
	
	$up_query=sprintf("SELECT * FROM %s WHERE `listOrder` > '%s'  ORDER BY `listOrder`",ITEM_TABLE,$listOrder);
	$up_set = mysql_query($up_query) or die(mysql_error());
	$row_up_set = mysql_fetch_assoc($up_set);
	$num_up_rows = mysql_num_rows($up_set);
	
	do {
	
	$query = sprintf("UPDATE %s SET listOrder = '%s' WHERE `%s` ='%s'",ITEM_TABLE,$listOrder,ITEM_ID,$row_up_set['itemID']);	
	mysql_query($query);
	echo mysql_error();
	$listOrder++;
	
	} while ($row_up_set = mysql_fetch_assoc($up_set));
	
	// delete entry

	$query = sprintf("DELETE FROM %s WHERE `%s` ='%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);	
	mysql_query($query);
	echo mysql_error();
	
	header("Location: manage".ITEM_NAME_PLURAL.".php?msg=del");
	exit;
}
if (isset($_GET['function']) and $_GET['function'] == 'order') {
		
	$newOrder=$_GET['displayOrder'];

	$curr_query=sprintf("SELECT * FROM `%s` WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
	
	$curr_set = mysql_query($curr_query) or die(mysql_error());
	$row_curr_set = mysql_fetch_assoc($curr_set);
	$currentOrder = $row_curr_set['listOrder'];
	
	
	// move up all those below first
	$up_query=sprintf("SELECT * FROM `%s` WHERE `listOrder` > '%s' ORDER BY `listOrder`",ITEM_TABLE,$currentOrder);
	$up_set = mysql_query($up_query) or die(mysql_error());
	$row_up_set = mysql_fetch_assoc($up_set);
	$listOrder = 	$currentOrder;
	do {
	
		$query = sprintf("UPDATE `%s` SET listOrder = '%s' WHERE `%s` ='%s'",ITEM_TABLE,$listOrder,ITEM_ID,$row_up_set[ITEM_ID]);	
		mysql_query($query);
		echo mysql_error();
		$listOrder++;
	
	} while ($row_up_set = mysql_fetch_assoc($up_set));	
	
	//set the mover order to 0 temporarily
	$query = sprintf("UPDATE `%s` SET `listOrder`=0 WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);	
	mysql_query($query);
	echo mysql_error();
	
	// move all down from new spot
	$up_query=sprintf("SELECT * FROM `%s` WHERE `listOrder` >= '%s'  ORDER BY `listOrder`",ITEM_TABLE,$newOrder);
	$up_set = mysql_query($up_query) or die(mysql_error());
	$row_up_set = mysql_fetch_assoc($up_set);
	$listOrder = 	$newOrder + 1;
	do {
	
		$query = sprintf("UPDATE `%s` SET listOrder = '%s' WHERE `%s` ='%s'",ITEM_TABLE,$listOrder,ITEM_ID,$row_up_set[ITEM_ID]);	
		mysql_query($query);
		echo mysql_error();
		$listOrder++;
	
	} while ($row_up_set = mysql_fetch_assoc($up_set));	
	
		// update the mover
		$query = sprintf("UPDATE `%s` SET `listOrder`='%s' WHERE `%s` = '%s'",ITEM_TABLE,$newOrder,ITEM_ID,$_GET[ITEM_ID]);	
		mysql_query($query);
		echo mysql_error();
		
		header("Location: manage".ITEM_NAME_PLURAL.".php");
		exit;
}
$photo_query=sprintf("SELECT * FROM %s ORDER by `listOrder`",ITEM_TABLE);
$photo_set = mysql_query($photo_query) or die(mysql_error());
$row_photo_set = mysql_fetch_assoc($photo_set);
$num_photos = mysql_num_rows($photo_set);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - Manage <?=ITEM_NAME_PLURAL?></title>

<script language="javascript">
<!--
function check()
{

   var d=document.form1;
 	if(d.title.value=='')
	  {
	   alert('Please Enter a Title');
	   d.title.focus();
	   return false;
	  }
	  if(d.text.value=='')
	  {
	   alert('Please Enter an Announcement');
	    d.text.focus();
	   return false;
	  }
	    

}
function surePhotoDelete(cid,nm)
{
	var getConfirm;
	getConfirm = confirm("Are you sure you want to delete this <?=ITEM_NAME?>?")
	if (getConfirm == true)
	{
		document.location.href = "manage<?=ITEM_NAME_PLURAL?>.php?function=delete&<?=ITEM_ID?>="+ cid 
	}

}

function changeOrder(cid,displayOrder,name)
{
	if (displayOrder == 0 || displayOrder > <?=mysql_num_rows($photo_set)?>) {
		alert ("Display order value must be between 1 and <?=mysql_num_rows($photo_set)?>");
		//document.getElementById(name).value = '99';
		setTimeout(function(){document.getElementById(name).select()}, 10);
		return false;
	}
	document.location.href = "manage<?=ITEM_NAME_PLURAL?>.php?function=order&<?=ITEM_ID?>="+ cid+"&displayOrder="+displayOrder 
}
-->
</script>
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
                      <td valign="top" class="tableContent"><?php if ($num_photos > 0) { ?>
		<table width="100%" border="0" cellpadding="5" cellspacing="2">
	 <tr>
	   <td class="adminTableItemHeading" width="32%" align="left">Question</td>
	   <td width="46%" align="left" class="adminTableItemHeading">Answer</td>
	   <td width="10%" align="center" class="adminTableItemHeading">Display Order  </td>
		<td width="6%" class="adminTableItemHeading"  align="center">Edit</td>
		<td width="6%" class="adminTableItemHeading" align="center">Delete </td>
        </tr>
	<?php 
	$color_flag = 0;
	
	do { 
	?>
	
	<tr <?php if ($row_photo_set['docStatus'] == "Approved") {
				echo ('class = "background_approved"');
			} elseif ($row_photo_set['docStatus'] == "Pending") { 
				echo ('class = "background_pending"');
			} elseif ($row_photo_set['docStatus'] == "Editing") { 
				echo ('class = "background_inProcess"');
			} elseif ($row_photo_set['docStatus'] == "Rejected") { 
				echo ('class = "background_rejected"');
			} elseif ($color_flag == 1) { 
				echo ('class = "background_lite"'); 
			} else { 
				echo ('class = "background_lite"'); 
			} ?>>
	  <td align="left" valign="top"  class="tableContent" ><?=stripslashes($row_photo_set['faqQuestion'])?></td>
	  <td  align="left" class="tableContent"><?=stripslashes(wordLimit($row_photo_set['faqAnswer'],35))?></td>
	  <td  align="center" valign="top" ><input name="listOrder" type="text" size="1" maxlength="3" id="displayOrder_<?=$idx?>" value="<?=$row_photo_set['listOrder']?>" onChange="changeOrder('<?=$row_photo_set[ITEM_ID]?>',this.value,this.id)"></td>
		<td align="center" valign="top"><a href="edit<?=ITEM_NAME?>.php?<?=ITEM_ID?>=<?=$row_photo_set[ITEM_ID]?>"><img src="images/write.gif" border="0"></a></td>
		<td  class="header_text_16px" align="center" valign="top"><a href="javascript: surePhotoDelete('<?=$row_photo_set[ITEM_ID]?>')"><img src="images/delete_icon.gif"  border="0"/></a></td>
	    </tr>
      
	  <?php 
	  if ($color_flag == 1) {
	  	$color_flag = 0;
	  } else {
		$color_flag = 1;
	  }
	  $idx++;
		} while ($row_photo_set = mysql_fetch_assoc($photo_set)); ?>
    </table>
		<?php } else { echo "No ".ITEM_NAME_PLURAL." found."; }?></td>
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