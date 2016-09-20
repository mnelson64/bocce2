<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=13;

$content_query=sprintf("SELECT * FROM pages WHERE pageID = '%s'",$pageID);
$content_set = mysql_query($content_query) or die(mysql_error());
$row_content_set = mysql_fetch_assoc($content_set);

$faq_query=sprintf("SELECT * FROM faqs ORDER BY `listOrder`");
//echo $faq_query;
$faq_set = mysql_query($faq_query) or die(mysql_error());
$row_faq_set = mysql_fetch_assoc($faq_set);
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
            <div id="faqArea">
				<?php
                do {?>
                <div class="faqUnit">
                    <div class="faqQuestion"><?=stripslashes($row_faq_set['faqQuestion'])?></div>
                    <div class="faqAnswer"><?=stripslashes($row_faq_set['faqAnswer'])?></div>
                </div>
                <?php } while ($row_faq_set = mysql_fetch_assoc($faq_set));?>
            </div>
            <div id="uplink"><a href="#header"><img src="<?=$path?>images/up_arrow.png"  alt="Back to Top"></a></div>
        </div>
        <div id="subRight">
        <?php include "sideBoxRight.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
</body>
</html>