<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=5;

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
            <div id="newsListingArea">
            <?php
            $news_query=sprintf("SELECT * FROM news WHERE `publish` = 1 ORDER BY `newsdate`");
            $news_set = mysql_query($news_query) or die(mysql_error());
            $row_news_set = mysql_fetch_assoc($news_set);
            do {
            ?>
            <div class="newsListingUnit">
            	<div class="newsListingDate"><?=date('M j, Y',$row_news_set['newsDate'])?></div>
                <?php if ($row_news_set['newsPDF'] != '') { ?>
                <div class="newsListingHeadline"><a href="<?=$path?>config/pdf/<?=$row_news_set['newsID']?>/<?=$row_news_set['newsPDF']?>" target="_blank"><?php if ($row_news_set['newsSource'] != '') echo $row_news_set['newsSource'].' - '?><?=stripslashes($row_news_set['newsHeadline'])?></a><a href="<?=$path?>config/pdf/<?=$row_news_set['newsID']?>/<?=$row_news_set['newsPDF']?>" target="_blank"><img src="<?=$path?>images/PDF-Icon.jpg" width="18" alt="PDF File"></a></div>
                <?php } elseif ($row_news_set['newsExternalURL'] != '') { ?>
                <div class="newsListingHeadline"><a href="<?=$row_news_set['newsExternalURL']?>" target="_blank"><?php if ($row_news_set['newsSource'] != '') echo $row_news_set['newsSource'].' - '?><?=stripslashes($row_news_set['newsHeadline'])?></a></div>
                <?php } else { ?>
                <div class="newsListingHeadline"><a href="<?=$path?>newsArticle/<?=$row_news_set['newsURL']?>/"><?php if ($row_news_set['newsSource'] != '') echo $row_news_set['newsSource'].' - '?><?=stripslashes($row_news_set['newsHeadline'])?></a></div>
                <?php } ?>
            </div>
            <?php } while ($row_news_set = mysql_fetch_assoc($news_set));?>
            </div>
        </div>
        <div id="subRight">
        <?php include "sideBoxRight.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
</body>
</html>