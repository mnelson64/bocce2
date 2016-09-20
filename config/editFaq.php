<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsFaq.php"; 
$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);
$num_items = mysql_num_rows($item_set);


if ($_POST['submit']) {
	if ($_GET['function'] == "update") {

		$query = sprintf("UPDATE %s SET faqQuestion = '%s', faqAnswer = '%s' WHERE `%s` ='%s'",ITEM_TABLE,addslashes($_POST['faqQuestion']),addslashes($_POST['faqAnswer']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
	
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
	
		$list_query=sprintf("SELECT * FROM `%s`",ITEM_TABLE);
		$list_set = mysql_query($list_query) or die(mysql_error());
		$listOrder = mysql_num_rows($list_set) + 1;
	
		$query = sprintf("INSERT INTO %s (faqQuestion,faqAnswer,listOrder) VALUES('%s','%s','%s')",ITEM_TABLE,addslashes($_POST['faqQuestion']),addslashes($_POST['faqAnswer']),$listOrder);	
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
<script type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../scripts/tinyInit.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
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
                      		<form action="edit<?=ITEM_NAME?>.php?function=add" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check();">
                       <?php } else {?>
               		  <form action="edit<?=ITEM_NAME?>.php?function=update" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check();">
                            <input name="<?=ITEM_ID?>" type="hidden" value="<?=$_GET[ITEM_ID]?>">
                       <?php }?>
                       
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
       <td width="13%" align="right" valign="top" class="adminTableItemHeading">Question</td>
       <td width="87%"><input name="faqQuestion" id="faqQuestion" type="text" class="input_text" size="80" value="<?=htmlspecialchars(stripslashes($row_item_set['faqQuestion']))?>"></td>
     </tr>
   
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Answer</td>
      <td width="87%"><textarea name="faqAnswer" id="faqAnswer" class="mceEditor" cols="20" rows="4"><?=stripslashes($row_item_set['faqAnswer'])?></textarea></td>
    </tr>
    <?php if ($_SESSION['adminType'] == 'Superuser' or $_SESSION['adminType'] == 'Editor') { ?>
    <?php } elseif ($row_item_set['docStatus'] != '') { ?>
    	<input name="docStatus" type="hidden" value="<?=$row_item_set['docStatus']?>">
    <?php } else { ?>
    	<input name="docStatus" type="hidden" value="Created">
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
</body>
</html>
<script type="text/javascript">
<!--
function check()
{
  	if($("#faqQuestion").val() == '') {
		alert('Please enter a question');
		$("#faqQuestion").focus();
		return false;	
	}
	if (tinyMCE.activeEditor.getContent() == '') {
		alert('Please provide an answer');
		tinyMCE.execInstanceCommand("faqAnswer", "mceFocus");
		return false;
	  } 
	 
}
-->
</script>