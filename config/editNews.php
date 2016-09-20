<?php
include "admin_app_top.php";
include "checkUser.php"; 
include "defsNews.php"; 
$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);
$num_items = mysql_num_rows($item_set);


if ($_POST['submit']) {
	if ($_GET['function'] == "update") {

		$query = sprintf("UPDATE %s SET newsSource = '%s', newsHeadline = '%s',  newsURL = '%s', newsExternalURL = '%s', newsEmbed = '%s', newsYoutubeID = '%s', newsDate = '%s',  newsArticle = '%s' WHERE `%s` ='%s'",ITEM_TABLE,mysql_real_escape_string($_POST['newsSource']),mysql_real_escape_string($_POST['newsHeadline']),mysql_real_escape_string($_POST['newsURL']),mysql_real_escape_string($_POST['newsExternalURL']),mysql_real_escape_string($_POST['newsEmbed']),mysql_real_escape_string($_POST['newsYoutubeID']),strtotime($_POST['newsDate']),mysql_real_escape_string($_POST['newsArticle']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
		
		if (isset($_POST['removePDF'])) {
			$query = sprintf("UPDATE %s SET  `newsPDF` = ''  WHERE %s = '%s'",ITEM_TABLE,ITEM_ID,$_POST[ITEM_ID]);	
			mysql_query($query);
			//echo $query;
			echo mysql_error();
		}
		
		// update any new file uploads
		$fileUpdate = false;
		if ($_FILES['newsPDF']['name'] != '') {
			$upload_dir_path = "pdf/".$_POST[ITEM_ID]."/";
			if (!is_dir($upload_dir_path)) {
				mkdir ($upload_dir_path);
			}
			$image_name = $_FILES['newsPDF']['name'];
			$upload_file = $upload_dir_path.$image_name;
			$file_uploaded_ok =(move_uploaded_file($_FILES['newsPDF']['tmp_name'], $upload_file));
			$imageString = " `newsPDF` = '".mysql_real_escape_string($_FILES['newsPDF']['name'])."' ";
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
	
		$list_query=sprintf("SELECT * FROM `%s`",ITEM_TABLE);
		$list_set = mysql_query($list_query) or die(mysql_error());
		$listOrder = mysql_num_rows($list_set) + 1;
	
		$query = sprintf("INSERT INTO %s (newsSource,newsHeadline,newsURL,newsExternalURL,newsEmbed,newsYoutubeID,newsDate,newsArticle,listOrder) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['newsSource']),mysql_real_escape_string($_POST['newsHeadline']),mysql_real_escape_string($_POST['newsURL']),mysql_real_escape_string($_POST['newsExternalURL']),mysql_real_escape_string($_POST['newsEmbed']),mysql_real_escape_string($_POST['newsYoutubeID']),strtotime($_POST['newsDate']),mysql_real_escape_string($_POST['newsArticle']),$listOrder);
		//echo $query;
		
		mysql_query($query);
		echo mysql_error();
		$myID = mysql_insert_id();
		
		// update any new file uploads
		$fileUpdate = false;
		if ($_FILES['newsPDF']['name'] != '') {
			$upload_dir_path = "pdf/".$myID."/";
			if (!is_dir($upload_dir_path)) {
				mkdir ($upload_dir_path);
			}
			$image_name = $_FILES['newsPDF']['name'];
			$upload_file = $upload_dir_path.$image_name;
			$file_uploaded_ok =(move_uploaded_file($_FILES['newsPDF']['tmp_name'], $upload_file));
			$imageString = " `newsPDF` = '".mysql_real_escape_string($_FILES['newsPDF']['name'])."' ";
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
<script type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../scripts/tinyInit.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
<script>
  $(document).ready(function() {
    $("#datepicker").datepicker({
			showOn: "button",
			buttonImage: "images/cal.gif",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			showAnim: 'fold',
			dateFormat: 'M d, yy',
			defaultDate: +0
});
  });
  </script>
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
                            <TD><a href="manage<?=ITEM_NAME_PLURAL?>.php" class="adminLink">Manage <?=ITEM_NAME_PLURAL?></a></TD>
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
      <td align="right" valign="top" class="adminTableItemHeading">Date</td>
      <td><input type="text" name="newsDate"  id="datepicker" value="<?php if ($row_item_set['newsDate'] != '' and $row_item_set['newsDate'] != 0) echo date('M j, Y',$row_item_set['newsDate'])?>"/>
        </td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Source</td>
      <td><input name="newsSource" id="newsSource" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['newsSource'])?>"></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Headline</td>
      <td width="87%"><input name="newsHeadline" id="newsHeadline" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['newsHeadline'])?>"></td>
    </tr>
   
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Internal URL</td>
      <td><input name="newsURL" id="newsURL" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['newsURL'])?>" onFocus="prefill();"><span class="help_text"> Letters, numbers and dashes only!</span></td>
    </tr>
     <tr>
       <td align="right" valign="top" class="adminTableItemHeading">External URL</td>
       <td class="tableContent"><input name="newsExternalURL" id="newsExternalURL" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['newsExternalURL'])?>"></td>
     </tr>
     <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Current File</td>
      <td class="tableContent"><?php if ($row_item_set['newsPDF'] != '' and file_exists("pdf/".$row_item_set['newsID']."/".$row_item_set['newsPDF'])) {?><a href="pdf/<?=$row_item_set['newsID']?>/<?=$row_item_set['newsPDF']?>"><?=$row_item_set['newsPDF']?></a>
        <?php } else { ?>
        No File Found
        <?php } ?>
        &nbsp;&nbsp;<label>
          <input type="checkbox" name="removePDF" id="removePDF">
          Delete This File</label></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Upload PDF</td>
      <td width="87%"><input type="file" name="newsPDF" id="newsPDF"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Embed Code</td>
      <td><textarea name="newsEmbed" cols="60" rows="4" ><?=stripslashes($row_item_set['newsEmbed'])?></textarea></td>
    </tr>
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Youtube ID</td>
      <td><span class="tableContent">
        <input name="newsYoutubeID" id="newsYoutubeID" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['newsYoutubeID'])?>">
      </span></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Article</td>
      <td width="87%"><textarea name="newsArticle" cols="20" rows="4" class="mceEditor"><?=stripslashes($row_item_set['newsArticle'])?></textarea></td>
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
function prefill () {
	if ($("#newsURL").val() == '') {
		internalURL = $("#newsHeadline").val().replace(/ /g,"-");
		$("#newsURL").val(internalURL);
	}
}
-->
</script>