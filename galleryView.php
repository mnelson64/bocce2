<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?=SITE_NAME?>: <?=$row_content_set['pageTitle']?></title>
<meta name="description" content="<?=$row_content_set['pageDescription']?>" />
<meta name="keywords" content="<?=$row_content_set['pageKeywords']?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0,width=device-width">

<link rel="stylesheet" href="<?=$path?>scripts/nivo-slider/themes/techeffex/techeffex.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=$path?>scripts/nivo-slider/themes/dark/dark.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=$path?>scripts/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
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
            <h1><?=$albumArray[$_GET['albumID']]['title']?></h1>
            
            <?=$albumArray[$_GET['albumID']]['description']?>
            <a href="<?=$path?>Gallery/"><< Back To Albums</a>
            <?php
				$slide_query=sprintf("SELECT * FROM photos WHERE `albumID` = '%s' ORDER BY `listOrder`",mysql_real_escape_string($_GET['albumID']));
				$slide_set = mysql_query($slide_query) or die(mysql_error());
				$row_slide_set = mysql_fetch_assoc($slide_set);
				?>
				<div class="slider-wrapper theme-dark">
					 <div id="slider" class="nivoSlider">
						<?php do {?>
							<img src="<?=$path?>config/photos/<?=$_GET['albumID']?>/<?= $row_slide_set['photoName']?>" title="<?= $row_slide_set['photoDescription']?>" data-thumb="<?=$path?>config/photos/<?=$_GET['albumID']?>/<?= $row_slide_set['photoName']?>" width="100%">
						<?php } while ($row_slide_set = mysql_fetch_assoc($slide_set));?>
					</div>
				</div>

        </div>
        <div id="subRight">
        <?php include "sideBoxRight.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
<script type="text/javascript" src="<?=$path?>scripts/nivo-slider/jquery.nivo.slider.js"></script>
<script type="text/javascript">
$(window).load(function() {
	$('#slider').nivoSlider({
				effect: 'fade', // Specify sets like: 'fold,fade,sliceDown'
				slices: 15,
				directionNav: false,
				controlNav: true,
				controlNavThumbs: true,
				animSpeed: 700, // Slide transition speed
				pauseTime: 3000 // How long each slide will show
							});
});
</script>
</body>
</html>