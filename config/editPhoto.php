<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsPhoto.php"; 
$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);
$num_items = mysql_num_rows($item_set);
$photoWidth = 600;
$photoHeight = 600;
if ($_POST['back'] == "Back") {

	header("Location: manage".ITEM_NAME_PLURAL.".php");
	exit;
}

if ($_POST['submit']) {
	
	if (isset($_POST['albumID'])) {
		$_SESSION['sAlbumID'] = $_POST['albumID'];
	}
	
	if ($_GET['function'] == "update") {
		
		$photo_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_POST[ITEM_ID]);
		//echo $photo_query;
		$photo_set = mysql_query($photo_query) or die(mysql_error());
		$row_photo_set = mysql_fetch_assoc($photo_set);
		
		// handle category change
		if ($row_photo_set['albumID'] != $_POST['albumID']) {
			// modify orders on old category, same as deleting a product
			$list_order = $row_photo_set['listOrder'];
		
			$up_query=sprintf("SELECT * FROM %s WHERE `listOrder` > '%s' AND `albumID` = '%s' ORDER BY `listOrder`",ITEM_TABLE,$list_order,$row_photo_set['albumID']);
			$up_set = mysql_query($up_query) or die(mysql_error());
			$row_up_set = mysql_fetch_assoc($up_set);
			$num_up_rows = mysql_num_rows($up_set);
			
			do {
			
			$query = sprintf("UPDATE %s SET listOrder = '%s' WHERE %s ='%s'",ITEM_TABLE,$list_order,ITEM_ID,$row_up_set[ITEM_ID]);	
			mysql_query($query);
			echo mysql_error();
			$list_order++;
			
			} while ($row_up_set = mysql_fetch_assoc($up_set));
			
			// now insert into new photos at the end of new album
			$new_vol_query=sprintf("SELECT * FROM %s WHERE `albumID` = '%s'",ITEM_TABLE,$_POST['albumID']);
			$new_vol_set = mysql_query($new_vol_query) or die(mysql_error());
			$new_vol_photo_order = mysql_num_rows($new_vol_set) + 1;
			
			$query = sprintf("UPDATE %s SET `albumID` = '%s', listOrder = '%s' WHERE %s ='%s'",ITEM_TABLE,$_POST['albumID'],$new_vol_photo_order,ITEM_ID,$_POST[ITEM_ID]);	
			mysql_query($query);
			echo mysql_error();
		}
		// end category change

		$query = sprintf("UPDATE %s SET  `photoDescription` = '%s'   WHERE `%s` = %s",ITEM_TABLE,mysql_real_escape_string($_POST['photoDescription']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
		
		// update any new file uploads
		$fileUpdate = false;
		if ($_FILES['photoName']['name'] != '') {
			//usage: upload_image($dst_height_max,$dst_width_max,$filename,$id,$checkSize,$top)
			$staffImage = upload_image($photoHeight,$photoWidth,'photoName',$_POST[albumID],false,false);
			$imageString = " `photoName` = '".mysql_real_escape_string($staffImage[0])."' ";
			$fileUpdate = true;
		}
		
		// update the db
		if ($fileUpdate) {
		$query = sprintf("UPDATE %s SET  %s  WHERE %s = '%s'",ITEM_TABLE,$imageString,ITEM_ID,$_POST[ITEM_ID]);	
		mysql_query($query);
		//echo $query;
		echo mysql_error();
		}
	
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
	
		$list_query=sprintf("SELECT * FROM `%s` WHERE `albumID` = '%s'",ITEM_TABLE,mysql_real_escape_string($_POST['albumID']));
		$list_set = mysql_query($list_query) or die(mysql_error());
		$listOrder = mysql_num_rows($list_set) + 1;
	
		$query = sprintf("INSERT INTO %s (albumID,photoDescription,listOrder) VALUES('%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['albumID']),mysql_real_escape_string($_POST['photoDescription']),$listOrder);	
		mysql_query($query);
		echo mysql_error();
		$myID = mysql_insert_id();
		
		// update any new file uploads
		$fileUpdate = false;
		if ($_FILES['photoName']['name'] != '') {
			//usage: upload_image($dst_height_max,$dst_width_max,$filename,$id,$checkSize,$top)
			$staffImage = upload_image($photoHeight,$photoWidth,'photoName',$_POST['albumID'],false,false);
			$imageString = " `photoName` = '".mysql_real_escape_string($staffImage[0])."' ";
			$fileUpdate = true;
		}
		
		// update the db
		if ($fileUpdate) {
		$query = sprintf("UPDATE %s SET  %s  WHERE %s = '%s'",ITEM_TABLE,$imageString,ITEM_ID,$myID);	
		mysql_query($query);
		//echo $query;
		echo mysql_error();
		}
		
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
                            <TD><a href="manage<?=ITEM_NAME_PLURAL?>.php" class="adminLink">Manage <?=ITEM_NAME_ENGLISH_PLURAL?></a></TD>
                  			<TD>|</TD>
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="6%">&nbsp;</td>
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
                      		<form action="edit<?=ITEM_NAME?>.php?function=add" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check();">
                       <?php } else {?>
               		  <form action="edit<?=ITEM_NAME?>.php?function=update" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check();">
                            <input name="<?=ITEM_ID?>" type="hidden" value="<?=$_GET[ITEM_ID]?>">
                       <?php }?>
                       
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
    
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td width="87%">&nbsp;</td>
    </tr>
   
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Album</td>
      <td colspan="3" class="tableContent"><select name="albumID" id="albumID">
      		<option value="select" >Select...</option>
            <?php
			$cat_query=sprintf("SELECT * FROM albums ORDER BY `albumTitle`");
			//echo $photo_query;
			$cat_set = mysql_query($cat_query) or die(mysql_error());
			$row_cat_set = mysql_fetch_assoc($cat_set);
			do { ?>
            <option value="<?=$row_cat_set['albumID']?>" <?php if ($row_cat_set['albumID'] == $row_item_set['albumID'] or $row_cat_set['albumID'] == $_SESSION['sAlbumID']) echo "selected";?>><?=$row_cat_set['albumTitle']?></option>
            <?php } while ($row_cat_set = mysql_fetch_assoc($cat_set));?>
    	</select></td>
    </tr>
    <tr>
        <td align="right" valign="top" class="adminTableItemHeading">Current Photo</td>
        <td colspan="3" class="tableContent"><?php if ($row_item_set['photoName'] != '' and file_exists("photos/".$row_item_set['photoID']."/".$row_item_set['photoName'])) {?><img src="photos/<?=$row_item_set['photoID']?>/<?=$row_item_set['photoName']?>" >
        <?php } else { ?>
        No Photo Found
        <?php } ?></td>
      </tr>
      <tr>
        <td align="right" valign="top" class="adminTableItemHeading">Photo</td>
        <td colspan="3"><input type="file" name="photoName" id="photoName"></td>
      </tr>
     <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Description</td>
      <td ><input name="photoDescription" type="text" value="<?=stripslashes($row_item_set['photoDescription'])?>" size="80" maxlength="100"></td>
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
function check()
{
   var d=document.form1;
   
	if (d.clubName.value == '') {
		alert('Please select a club.');
		d.clubName.focus();
		return false;
	  }
	  
	 if (d.deptName.value == '') {
		alert('Please select a department.');
		d.deptName.focus();
		return false;
	  } 
	  
	if (d.campName.value == '') {
		alert('Please provide a camp name.');
		d.campName.focus();
		return false;
	  }
	 
}
-->
</script>