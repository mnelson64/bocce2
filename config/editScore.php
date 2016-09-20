<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsScore.php"; 

// AJAX 
// end AJAX
$item_query=sprintf("SELECT * FROM games WHERE `gameDate` = '%s' ORDER BY `gameCourt`",$_GET['date']);
//echo $item_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);

if ($_POST['submit']) {
	
	// loop on 4 courts
	foreach ($courtArray as $courtID) {
		// check if game scores exists
		$scores_query=sprintf("SELECT * FROM scores WHERE `scoreGame` = '%s'",mysql_real_escape_string($_POST['gameID_'.$courtID]));
		//echo $scores_query;
		$scores_set = mysql_query($scores_query) or die(mysql_error());
		$row_scores_set = mysql_fetch_assoc($scores_set);
		if (mysql_num_rows($scores_set) > 0) {
						
			$query = sprintf("UPDATE %s SET `scoreGame` = '%s',`score1a` = '%s',`score1b` = '%s',`score2a` = '%s',`score2b` = '%s',`score3a` = '%s',`score3b` = '%s' WHERE `%s` = %s",ITEM_TABLE,mysql_real_escape_string($_POST['gameID_'.$courtID]),mysql_real_escape_string($_POST['game1TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game1TeamBScore_'.$courtID]),mysql_real_escape_string($_POST['game2TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game2TeamBScore_'.$courtID]),mysql_real_escape_string($_POST['game3TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game3TeamBScore_'.$courtID]),ITEM_ID,$row_scores_set['scoreID']);	
			//echo $query;
			//exit;
			mysql_query($query);
			echo mysql_error();
			
		} else {
			
			
			$query = sprintf("INSERT INTO %s (scoreGame,score1a,score1b,score2a,score2b,score3a,score3b) VALUES('%s','%s','%s','%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['gameID_'.$courtID]),mysql_real_escape_string($_POST['game1TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game1TeamBScore_'.$courtID]),mysql_real_escape_string($_POST['game2TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game2TeamBScore_'.$courtID]),mysql_real_escape_string($_POST['game3TeamAScore_'.$courtID]),mysql_real_escape_string($_POST['game3TeamBScore_'.$courtID]));	
			//echo $query;
			//exit;
			mysql_query($query);
			echo mysql_error();
		
			
		}
	} // court loop
	header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
	exit;
	
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?=SITE_NAME?> - <?php if ($_GET['f'] == 'add') { ?>Add<?php } else {?>Edit<?php }?>&nbsp;<?=ITEM_NAME?></title>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
<?php include "../include/jquery.php";?>
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
                      <form action="edit<?=ITEM_NAME?>.php" method="post" enctype="multipart/form-data" name="form1">
                       
		<table width="70%" border="0" cellspacing="0" cellpadding="5">
    
    <tr>
      <td width="16%" align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td colspan="3" ><?=date('l F j, Y',$_GET['date'])?></td>
    </tr>
    
    <?php do { 
	$scores_query=sprintf("SELECT * FROM scores WHERE `scoreGame` = '%s'",mysql_real_escape_string($row_item_set['gameID']));
	//echo $scores_query;
	$scores_set = mysql_query($scores_query) or die(mysql_error());
	$row_scores_set = mysql_fetch_assoc($scores_set);
	?>
    <input name="gameID_<?=$row_item_set['gameCourt']?>" type="hidden" value="<?=$row_item_set['gameID']?>">
    <tr class = "backgroundStatusGreen">
      <td align="right" valign="top" ><strong>Court:</strong> <?=$row_item_set['gameCourt']?></td>
      <td width="24%"><strong>Team A:</strong> <?=$teamArray[$_SESSION['sGameYear']][$row_item_set['gameTeamA']]['teamName']?></td>
      <td width="26%"><strong>Team B:</strong> <?=$teamArray[$_SESSION['sGameYear']][$row_item_set['gameTeamB']]['teamName']?></td>
      <td width="34%">&nbsp;</td>
    </tr>
    <tr class = "background_lite">
      <td align="right" valign="top" class="adminTableItemHeading">Game 1</td>
      <td ><select name="game1TeamAScore_<?=$row_item_set['gameCourt']?>" id="game1TeamAScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score1a'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select>
        </td>
      <td ><select name="game1TeamBScore_<?=$row_item_set['gameCourt']?>" id="game1TeamBScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score1b'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select></td>
      <td >&nbsp;</td>
    </tr>
     <tr class = "background_dark">
      <td align="right" valign="top" class="adminTableItemHeading">Game 2</td>
      <td ><select name="game2TeamAScore_<?=$row_item_set['gameCourt']?>" id="game2TeamAScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score2a'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select>
        </td>
      <td ><select name="game2TeamBScore_<?=$row_item_set['gameCourt']?>" id="game2TeamBScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score2b'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select></td>
      <td >&nbsp;</td>
    </tr>
     <tr class = "background_lite">
      <td align="right" valign="top" class="adminTableItemHeading">Game 3</td>
      <td ><select name="game3TeamAScore_<?=$row_item_set['gameCourt']?>" id="game3TeamAScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score3a'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select>
        </td>
      <td ><select name="game3TeamBScore_<?=$row_item_set['gameCourt']?>" id="game3TeamBScore_<?=$row_item_set['gameCourt']?>">
        <?php for ($i = 0; $i < 13; $i++) {?>
        <option value="<?=$i?>" <?php if ($row_scores_set['score3b'] == $i) echo 'selected="selected"';?>><?=$i?></option>
        <?php } ?>
        </select></td>
      <td >&nbsp;</td>
    </tr>
     <tr>
       <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
       <td >&nbsp;</td>
       <td >&nbsp;</td>
       <td >&nbsp;</td>
     </tr>
   
   
    <?php } while ($row_item_set = mysql_fetch_assoc($item_set));?>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td colspan="3" ><input name="submit" type="submit" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;<input name="back" type="submit" value="Back" onClick="form1.action='manage<?=ITEM_NAME_PLURAL?>.php'; form1.onsubmit=function(){};return true;"></td>
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
