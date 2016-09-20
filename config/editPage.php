<?php
include "admin_app_top.php";
if($_SESSION['chklogin']=="")
header("Location:index.php"); 
include "defsPage.php"; 
$item_query=sprintf("SELECT * FROM %s WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_GET[ITEM_ID]);
//echo $photo_query;
$item_set = mysql_query($item_query) or die(mysql_error());
$row_item_set = mysql_fetch_assoc($item_set);
$num_items = mysql_num_rows($item_set);

if ($_POST['back'] == "Back") {

	header("Location: manage".ITEM_NAME_PLURAL.".php");
	exit;
}

function updateHT($path) {
	$ht_query=sprintf("SELECT * FROM pages");
	$ht_set = mysql_query($ht_query) or die(mysql_error());
	$row_ht_set = mysql_fetch_assoc($ht_set);
	
	$newContent = "Options +FollowSymLinks\r\n";
	$newContent .= "Options -MultiViews\r\n";
	$newContent .= "RewriteBase / \r\n";
	$newContent .= "RewriteEngine on\r\n";
	$newContent .= "ErrorDocument 404 /error.php \r\n";
	if ($path == 'http://localhost/bocce2/') {
		$newContent .= "RewriteRule ^Schedules/scores/(.*)/$ http://localhost/bocce2/schedules.php?gameID=$1 [L]\r\n";
		$newContent .= "RewriteRule ^Standings/scores/(.*)/$ http://localhost/bocce2/standings.php?teamID=$1 [L]\r\n";
		$newContent .= "RewriteRule ^Standings/(.*)/$ http://localhost/bocce2/standings.php?year=$1 [L]\r\n";
		$newContent .= "RewriteRule ^StandingsOverall/(.*)/$ http://localhost/bocce2/standings_overall.php?year=$1 [L]\r\n";
		$newContent .= "RewriteRule ^StandingsOverall/$ http://localhost/bocce2/standings_overall.php [L]\r\n";
		$newContent .= "RewriteRule ^newsArticle/(.*)/$ http://localhost/bocce2/news_article.php?newsURL=$1 [L]\r\n";
		$newContent .= "RewriteRule ^galleryView/(.*)/$ http://localhost/bocce2/galleryView.php?albumID=$1 [L]\r\n";
	} else {
		$newContent .= "RewriteRule ^Schedules/scores/(.*)/$ schedules.php?gameID=$1 [L]\r\n";
		$newContent .= "RewriteRule ^Standings/scores/(.*)/$ standings.php?teamID=$1 [L]\r\n";
		$newContent .= "RewriteRule ^Standings/(.*)/$ standings.php?year=$1 [L]\r\n";
		$newContent .= "RewriteRule ^StandingsOverall/(.*)/$ standings_overall.php?year=$1 [L]\r\n";
		$newContent .= "RewriteRule ^StandingsOverall/$ standings_overall.php [L]\r\n";
		$newContent .= "RewriteRule ^newsArticle/(.*)/$ news_article.php?newsURL=$1 [L]\r\n";
		$newContent .= "RewriteRule ^galleryView/(.*)/$ galleryView.php?albumID=$1 [L]\r\n";
	}
	if ($path != 'http://localhost/bocce2/') {
		$newContent .= "RewriteCond %{HTTP_HOST} !^www\.\r\n";
		$newContent .= "RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [R=301,L]\r\n";
	}
	do {
		if ($path == 'http://localhost/bocce2/') {
			$newContent .= "RewriteRule ^".$row_ht_set['pageURL']."/$ http://localhost/bocce2/".$row_ht_set['pageFile']." [L]\r\n"; 
		} else {
			$newContent .= "RewriteRule ^".$row_ht_set['pageURL']."/$ ".$row_ht_set['pageFile']." [L]\r\n"; 
		}
	} while ($row_ht_set = mysql_fetch_assoc($ht_set));	
	
	file_put_contents("../.htaccess", $newContent);
			  
}

if ($_POST['submit']) {
	if ($_GET['function'] == "update") {
		
		$curr_query=sprintf("SELECT * FROM `%s` WHERE `%s` = '%s'",ITEM_TABLE,ITEM_ID,$_POST[ITEM_ID]);
		$curr_set = mysql_query($curr_query) or die(mysql_error());
		$row_curr_set = mysql_fetch_assoc($curr_set);
		
		if ($_POST['pageFile'] != $row_curr_set['pageFile']) {
			if (file_exists("../".$_POST['pageFile'])) {
				header("Location: edit".ITEM_NAME.".php?".ITEM_ID."=".$_POST[ITEM_ID]."&msg=fn");
				exit;
			} else {
				rename("../".$row_curr_set['pageFile'],"../".$_POST['pageFile']);
			}
		}

		$query = sprintf("UPDATE %s SET  `pageFile` = '%s', `pageTitle` = '%s', `pageHeading` = '%s',`pageURL` = '%s',`pageDescription` = '%s',`pageKeywords` = '%s',`pageContent` = '%s'   WHERE `%s` = %s",ITEM_TABLE,mysql_real_escape_string($_POST['pageFile']),mysql_real_escape_string($_POST['pageTitle']),mysql_real_escape_string($_POST['pageHeading']),mysql_real_escape_string($_POST['pageURL']),mysql_real_escape_string($_POST['pageDescription']),mysql_real_escape_string($_POST['pageKeywords']),mysql_real_escape_string($_POST['pageContent']),ITEM_ID,$_POST[ITEM_ID]);	
		//echo $query;
		mysql_query($query);
		echo mysql_error();
		
		updateHT($path);
		
		header("Location: manage".ITEM_NAME_PLURAL.".php?msg=up");
		exit;
		
	} else {
	
		$query = sprintf("INSERT INTO %s (pageFile,pageTitle,pageHeading,pageURL,pageDescription,pageKeywords,pageContent,listOrder) VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($_POST['pageFile']),mysql_real_escape_string($_POST['pageTitle']),mysql_real_escape_string($_POST['pageHeading']),mysql_real_escape_string($_POST['pageURL']),mysql_real_escape_string($_POST['pageDescription']),mysql_real_escape_string($_POST['pageKeywords']),mysql_real_escape_string($_POST['pageContent']),'');	
		mysql_query($query);
		$newID = mysql_insert_id();
		echo mysql_error();
		
		if (file_exists("../".$_POST['pageFile'])) {
			header("Location: edit".ITEM_NAME.".php?".ITEM_ID."=".$newID."&msg=fn");
			exit;
		}
		
		copy("../include/generic.php","../".$_POST['pageFile']);
		$content = file_get_contents("../".$_POST['pageFile']);
		$newContent = str_replace('$id',$newID,$content);		
		file_put_contents("../".$_POST['pageFile'], $newContent);
		
		updateHT($path);
		
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
<?php include "../include/jquery.php";?>
<link href="admin_styles.css" rel="stylesheet" type="text/css">
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
      <td class="alert"><?php if ($_GET['msg'] == 'fn') echo "The filename you chose is already in use. Delete the original or choose another name."?></td>
    </tr>
    
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Page File Name</td>
      <td><input name="pageFile" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageFile'])?>"> 
        <span class="help_text">*Must have a .php extension</span></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Page Title</td>
      <td width="87%"><input name="pageTitle" id="pageTitle" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageTitle'])?>" onBlur="prefill();"></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Page Heading</td>
      <td width="87%"><input name="pageHeading" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageHeading'])?>" id="pageHeading"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Page URL</td>
      <td><input name="pageURL" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageURL'])?>"  id="pageURL"></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Meta Description</td>
      <td width="87%"><input name="pageDescription" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageDescription'])?>"></td>
    </tr>
    <tr>
      <td width="13%" align="right" valign="top" class="adminTableItemHeading">Meta Keywords</td>
      <td width="87%"><input name="pageKeywords" type="text" class="input_text" size="67" value="<?=stripslashes($row_item_set['pageKeywords'])?>"></td>
    </tr>
    <tr>
      <td align="right" valign="top" class="adminTableItemHeading">Content</td>
      <td ><textarea name="pageContent" id="pageContent" cols="45" rows="5"><?=stripslashes($row_item_set['pageContent'])?></textarea></td>
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
<script type="text/javascript">
<!--
function prefill () {
	if ($("#pageHeading").val() == '') {
		pageHeading = $("#pageTitle").val();
		$("#pageHeading").val(pageHeading);
	}
	if ($("#pageURL").val() == '') {
		internalURL = $("#pageTitle").val().replace(/ /g,"-");
		$("#pageURL").val(internalURL);
	}
}
-->
</script>
</body>
</html>
