<?php
include "admin_app_top.php";
include "checkUser.php";
include "defsScore.php"; 
$msg = $_GET['msg'];

// AJAX Stuff //////////////////////////////////////////
/////////////////////////////////////////////////////////

if (isset($_GET['function']) and $_GET['function'] == 'delete') {
		
	// delete entry
	$query = sprintf("DELETE FROM %s WHERE `%s` ='%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);	
	mysql_query($query);
	echo mysql_error();
	
	header("Location: manage".ITEM_NAME_PLURAL.".php?msg=del");
	exit;
}

if (!(isset($_SESSION['sGameYear']))) {
	$_SESSION['sGameYear'] = date('Y');
}
if (isset($_GET['gameYear'])) {
	$_SESSION['sGameYear'] = $_GET['gameYear'];
}
if ($_SESSION['sGameYear'] == 'All' or !(isset($_SESSION['sGameYear']))) {
	$yearString = '';
} else {
	$yearString = 'AND gameYear = \''.$_SESSION['sGameYear'].'\'';
}

if (isset($_GET['gameNight'])) {
	$_SESSION['sGameNight'] = $_GET['gameNight'];
}
if ($_SESSION['sGameNight'] == 'All' or !(isset($_SESSION['sGameNight']))) {
	$nightString = '';
} else {
	$nightString = 'AND gameNight = \''.$_SESSION['sGameNight'].'\'';
}


$photo_query=sprintf("SELECT * FROM games WHERE 1 %s %s GROUP BY gameDate  ORDER BY `gameDate`",$yearString,$nightString);	
echo $photo_query;
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
function surePhotoDelete(cid,fairID)
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
                      <td width="1%">&nbsp;</td>
                      <td width="97%"><TABLE border=0 align="left" cellPadding=3 cellSpacing=0 class=body>
                    		<TR> 
                  			<TD><a href="admin_page.php" class="adminLink">Return to Admin</a></TD>
                  			<TD>|</TD>
                  			<TD><a href="logout.php" class="adminLink">Logout</a></TD>
                			</TR>
   				 	  </TABLE></td>
                      <td width="2%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td valign="top" class="tableHeading" align="center"><div id="manageHeading">Manage <?=ITEM_NAME_PLURAL?> </div>                       <!-- <div id="addLink" align="right"><a href="edit<?=ITEM_NAME?>.php?f=add"><span class="addSign">+</span>&nbsp;&nbsp;Add <?=ITEM_NAME?></a></div>--></td>
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
                      <td valign="top" class="tableContent">
                       <div class="selectLabel">Year</div>
                     <div class="selectItem">
                       <select name="gameYear" id="gameYear" onChange="window.location =this.options[this.selectedIndex].value">
                         <option value="manageScores.php?gameYear=All" <?php if ($_SESSION['sGameYear'] == 'All') echo "selected=\"selected\"" ?>>Show All</option>      
                         <?php 
                      for ($y=2012; $y <= (date('Y')+2); $y++) { ?>
                         <option value="manageScores.php?gameYear=<?=$y?>" <?php if ($_SESSION['sGameYear'] == $y) echo "selected=\"selected\"" ?>><?=$y?></option>
                         <?php } ?>
                         </select>                          
                       </div>
                       <div class="selectLabel">Night</div>
                     <div class="selectItem">
                       <select name="gameNight" id="gameNight" onChange="window.location =this.options[this.selectedIndex].value">
                         <option value="manageScores.php?gameNight=All" <?php if ($_SESSION['sGameNight'] == 'All') echo "selected=\"selected\"" ?>>Show All</option>      
                         <?php 
                      foreach ($nightArray as $night) { ?>
                         <option value="manageScores.php?gameNight=<?=$night?>" <?php if ($_SESSION['sGameNight'] == $night) echo "selected=\"selected\"" ?>><?=$night?></option>
                         <?php } ?>
                         </select>                          
                       </div>
                       </td>
	   
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
		<table width="100%" border="0" cellpadding="5" cellspacing="2">
	 <tr>
	   <td class="adminTableItemHeading" width="10%" align="left">Year</td>
	   <td class="adminTableItemHeading" width="16%" align="left">Night</td>
	   <td class="adminTableItemHeading" width="14%" align="left">Date</td>
	   <td width="8%" class="adminTableItemHeading"  align="center">Enter Scores</td>
	   </tr>
	<?php 
	$color_flag = 0;
	
	do { 
	?>
	<tr <?php if ($color_flag == 1) { echo ('class = "background_lite"'); } else { echo ('class = "background_dark"'); } ?>>
	  <td align="left" valign="top"  class="tableContent" ><?=stripslashes($row_photo_set['gameYear'])?></td>
	  <td align="left" valign="top"  class="tableContent" ><?=stripslashes($row_photo_set['gameNight'])?></td>
	  <td align="left" valign="top"  class="tableContent" ><?php if ($row_photo_set['gameDate'] != '') echo date('M j, Y',$row_photo_set['gameDate'])?></td>
	  <td align="center" valign="top" ><a href="edit<?=ITEM_NAME?>.php?date=<?=$row_photo_set['gameDate']?>"><img src="images/write.gif" border="0"></a></td>
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
		<?php } else { echo "No games found.";}?></td>
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
function changeApprove(id)
{
	$.get("manageRegistrations.php",{ approve: id},
	  function displayData(xmlDoc,status,xmlHttp)
		{
			if ($("#regStatus_"+id).html() == 'Pending') {
				$("#regStatus_"+id).html('Approved');
				$("#regRow"+id).removeClass();
				$("#regRow"+id).addClass('backgroundStatusGreen');
			} else {
				$("#regStatus_"+id).html('Pending');
				$("#regRow"+id).removeClass();
				$("#regRow"+id).addClass('backgroundStatusYellow');
			}
			
		}  
  	) // get 

}
-->
</script>
</body>
</html>