<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsPhoto.php";

$msg = $_GET['msg'];

if (isset($_GET['function']) and $_GET['function'] == 'delete') {
	
	
	$del_query=sprintf("SELECT * FROM %s WHERE `%s` ='%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
	
	$del_set = mysql_query($del_query) or die(mysql_error());
	$row_del_set = mysql_fetch_assoc($del_set);
	
	// shuffle photo orders up
	$listOrder = $row_del_set['listOrder'];
	
	$up_query=sprintf("SELECT * FROM %s WHERE `listOrder` > '%s' AND `albumID` = '%s' ORDER BY `listOrder`",ITEM_TABLE,$listOrder,$row_del_set['albumID']);
	$up_set = mysql_query($up_query) or die(mysql_error());
	$row_up_set = mysql_fetch_assoc($up_set);
	$num_up_rows = mysql_num_rows($up_set);
	
	do {
	
	$query = sprintf("UPDATE %s SET listOrder = '%s' WHERE `%s` ='%s'",ITEM_TABLE,$listOrder,ITEM_ID,$row_up_set[ITEM_ID]);	
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
	$up_query=sprintf("SELECT * FROM `%s` WHERE `listOrder` > '%s' AND `albumID` = '%s' ORDER BY `listOrder`",ITEM_TABLE,$currentOrder,$row_curr_set['albumID']);
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
	$up_query=sprintf("SELECT * FROM `%s` WHERE `listOrder` >= '%s'  AND `albumID` = '%s' ORDER BY `listOrder`",ITEM_TABLE,$newOrder,$row_curr_set['albumID']);
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

if (isset($_GET['albumID'])) {
	$_SESSION['sAlbumID'] = $_GET['albumID'];
}
if (isset($_SESSION['sAlbumID']) and $_SESSION['sAlbumID'] != 'All') {
	$albumString = " AND `albumID` ='".$_SESSION['sAlbumID']."'"; 
} else {
	$albumString = '';
}

$photo_query=sprintf("SELECT * FROM %s  WHERE 1 %s ORDER BY `albumID`,`listOrder`",ITEM_TABLE,$albumString);
$photo_set = mysql_query($photo_query) or die(mysql_error());
$row_photo_set = mysql_fetch_assoc($photo_set);
$num_photos = mysql_num_rows($photo_set);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - Manage <?=ITEM_NAME_ENGLISH_PLURAL?></title>

<script language="javascript">
<!--
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
                            <TD><a href="manageAlbums.php" class="adminLink">Manage Albums</a></TD>
                  			<TD>|</TD>
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="6%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableHeading" align="center"><div id="manageHeading">Manage <?=ITEM_NAME_ENGLISH_PLURAL?> </div>                        <div id="addLink" align="right"><a href="edit<?=ITEM_NAME?>.php?f=add"><span class="addSign">+</span>&nbsp;&nbsp;Add <?=ITEM_NAME_ENGLISH?></a></div></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="alert">
					  <?php if ($_GET['msg'] == 'del') { echo ITEM_NAME_ENGLISH." deleted successfully."; 
							} elseif ($_GET['msg'] == 'new') { echo ITEM_NAME_ENGLISH." added successfully.";
							} elseif ($_GET['msg'] == 'up') { echo ITEM_NAME_ENGLISH." updated successfully.";
							}?></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top"><?php ///////////////////////// Select Album /////////////////////////?>
                              	<div id="selectAlbum">
                                <div class="selectLabel" id="selectAlbumLabel">Select Album</div>
                                  <select name="albumID" id="albumID" onChange="window.location =this.options[this.selectedIndex].value">
                                    <option value="managePhotos.php?albumID=All" <?php if ($_SESSION['sAlbumID'] == 'All') echo "selected=\"selected\"";?>>Show All</option>
                                    <?php foreach ($albumArray as $key => $album) { ?>
                                    <option value="managePhotos.php?albumID=<?=$key?>" <?php if ($_SESSION['sAlbumID'] == $key) { echo "selected=\"selected\"";}?>><?=$album['title']?></option>
                                    <?php } ?>
                                  </select>
                                  
                                </div></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableContent">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableContent"><?php if ($num_photos > 0) { ?>
		<table width="66%" border="0" cellpadding="5" cellspacing="2">
	 <tr>
	   <td class="adminTableItemHeading" width="14%" align="left">Photo</td>
	   <td class="adminTableItemHeading" width="53%" align="left">Description</td>
	   <td class="adminTableItemHeading" width="17%" align="left">Album</td>
       <?php if (isset($_SESSION['sAlbumID']) and $_SESSION['sAlbumID'] != 'All') {?>
	   <td width="6%" class="adminTableItemHeading"  align="center">Display Order</td>
       <?php } ?>
	   <td width="4%" class="adminTableItemHeading"  align="center">Edit</td>
	   <td width="6%" class="adminTableItemHeading" align="center">Delete </td>
	   </tr>
	<?php 
	$color_flag = 0;
	
	do { 
	?>
	
	<tr <?php if ($color_flag == 1) { echo ('class = "background_lite"'); } else { echo ('class = "background_dark"'); } ?>>
	  <td align="left" valign="top"  class="tableContent" ><?php if ($row_photo_set['photoName'] != '' and file_exists("photos/".$row_photo_set['albumID']."/".$row_photo_set['photoName'])) {?><img src="photos/<?=$row_photo_set['albumID']?>/<?=$row_photo_set['photoName']?>"   width="90">
	    <?php } else { ?>
	    No Photo Found
	    <?php } ?></td>
	  <td align="left" valign="top"  class="tableContent" ><?=wordLimit(stripslashes($row_photo_set['photoDescription']),50)?></td>
      <td align="left" valign="top"  class="tableContent" ><?=$albumArray[$row_photo_set['albumID']]['title']?></td>
      <?php if (isset($_SESSION['sAlbumID']) and $_SESSION['sAlbumID'] != 'All') {?>
	  <td  align="center" valign="top"><input  id="displayOrder_<?=$idx?>" type="text" size="1" maxlength="3" value="<?=$row_photo_set['listOrder']?>" onChange="changeOrder('<?=$row_photo_set[ITEM_ID]?>',this.value,this.id)"></td>
      <?php } ?>
	  <td align="center" valign="top" ><a href="edit<?=ITEM_NAME?>.php?<?=ITEM_ID?>=<?=$row_photo_set[ITEM_ID]?>"><img src="images/write.gif" border="0"></a></td>
		<td align="center" valign="top"  class="header_text_16px"><a href="javascript: surePhotoDelete('<?=$row_photo_set[ITEM_ID]?>')"><img src="images/delete_icon.gif"  border="0"/></a></td>
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
		<?php } else { echo "No ".ITEM_NAME_PLURAL." found.";}?></td>
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
function changePublish(id)
{
var xmlHttp;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  

var url="manageJobPostings.php";
url=url+"?publish="+id;

xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}


  -->
</script>