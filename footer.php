<?php if ($myFileName == 'index.php') {?>
<footer id="footerHome">
<?php } else {?>
<footer>
<?php }?>
    <nav>
        <ul>
         	<li id="homeNav"><a href="<?=$path?>">Home</a></li>
            <li><a href="<?=$path?>The-League/">The League</a></li>
            <li><a href="<?=$path?>About-Bocce-Ball/" >About Bocce Ball</a></li>
            <li><a href="<?=$path?>Standings/">Standings</a></li>
            <li><a href="<?=$path?>Schedules/">Schedules</a></li>
            <li><a href="<?=$path?>News-Events/">News & Events</a></li>
            <li><a href="<?=$path?>Gallery/">Gallery</a></li>
            <li><a href="<?=$path?>Contact/" class="last">Contact</a></li>
        </ul>
    </nav>
    <div id="footerText">
    	&copy;<?php echo date('Y') ?> Sonoma County Bocce Club.
    </div>
    <div id="footerIconUnit">
    	<a href="http://www.facebook.com/socobocce" target="_blank"><img src="<?=$path?>images/png/facebook.png" alt="link to facebook" role="button" tabindex="0"></a>
        <a href="https://twitter.com/SoCo_Bocce" target="_blank"><img src="<?=$path?>images/png/twitter.png" alt="link to twitter" role="button" tabindex="0"></a>
        <a href="https://plus.google.com/111150291348891321433" rel="publisher" target="_blank"><img src="<?=$path?>images/png/googleplus.png"  alt="link to Google Plus page" role="button" tabindex="0"></a>
    </div>
    <div id="footerTechLink"><a href="http://www.techeffex.com/santa-rosa-website-design/" target="_blank">Website Design</a> by <a href="http://www.techeffex.com/" target="_blank">Techeffex</a></div>
</footer>
<script>
var viewportWidth = $(window).width();
var viewportHeight = $(window).height();
//$("#viewPort").html("width = "+viewportWidth+", height ="+viewportHeight);
$(window).resize(function() {
	viewportWidth = $(window).width();
	viewportHeight = $(window).height();
	//$("#viewPort").html("width = "+viewportWidth+", height ="+viewportHeight);
	$("#menu ul").css("display","none");
});
</script>