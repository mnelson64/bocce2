<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=6;

$content_query=sprintf("SELECT * FROM pages WHERE pageID = '%s'",$pageID);
$content_set = mysql_query($content_query) or die(mysql_error());
$row_content_set = mysql_fetch_assoc($content_set);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?=SITE_NAME?>: <?=$row_content_set['pageTitle']?></title>
<meta name="description" content="<?=$row_content_set['pageDescription']?>" />
<meta name="keywords" content="<?=$row_content_set['pageKeywords']?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0,width=device-width">
<link href="<?=$path?>site.css" rel="stylesheet" type="text/css" />
<?php include "include/jquery.php";?>
<?php include "include/analytics.php"?>
</head>

<body class="sub">

<div id="main">
	<?php include "header.php";?>
    
  <div id="mainContentWrapper">
        <div id="viewPort">
        </div>
        <div id="subLeft">
            <h1><?=stripslashes($row_content_set['pageHeading'])?></h1>
            <?=stripslashes($row_content_set['pageContent'])?>
            <div id="galleryArea">
            <?php foreach ($albumArray as $key => $album) {?>
            <div class="galleryUnit">
            	<div class="galleryImage"><a href="<?=$path?>galleryView/<?=$key?>/"><img src="<?=$path?>config/photos/<?=$key?>/<?=$album['thumb']?>"></a></div>
                <div class="galleryTitle"><a href="<?=$path?>galleryView/<?=$key?>/"><?=$album['title']?></a></div>
            </div>
            
            <?php }?>
            </div>

        </div>
        <div id="subRight">
        <?php include "sideBoxRight.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
</body>
</html>