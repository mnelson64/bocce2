<script src="<?=$path?>scripts/superfish-master/dist/js/hoverIntent.js"></script>
<script src="<?=$path?>scripts/superfish-master/dist/js/superfish.js"></script>
<nav id="navHorizontal">
    <ul class="sf-menu" id="menu">
        <li id="homeNav"><a href="<?=$path?>">Home</a></li>
        <li><a href="<?=$path?>The-League/" <?php if ($myFileName == 'the_league.php') echo 'class="active"';?>>The League</a>
        	<ul>
                <li><a href="<?=$path?>The-Board/" >The Board</a></li>
                <li><a href="<?=$path?>Committees/" >Committees</a></li>
                <li><a href="<?=$path?>Registration/" >Registration</a></li>
                <li><a href="http://sonomacountyboccefederation.org/" target="_blank" >Federation Website</a></li>
            </ul>
        </li>
        <li><a href="<?=$path?>About-Bocce-Ball/" <?php if ($myFileName == 'about_bocce_ball.php') echo 'class="active"';?>>About Bocce Ball</a>
        	<ul>
                <li><a href="<?=$path?>Rules/" >Rules</a></li>
                <li><a href="<?=$path?>Bocce-Glossary/" >Glossary</a></li>
                <li><a href="<?=$path?>Frequently-Asked-Questions/" >FAQs</a></li>
                <li><a href="<?=$path?>Bocce-Resources/" >Bocce Resources</a></li>
            </ul>	
        </li>
        <li><a href="<?=$path?>Standings/" <?php if ($myFileName == 'standings.php') echo 'class="active"';?>>Standings</a>
        	<ul>
                <li><a href="<?=$path?>StandingsOverall/" >Overall Standings</a></li>                
            </ul>
        </li>
        <li><a href="<?=$path?>Schedules/" <?php if ($myFileName == 'schedules.php') echo 'class="active"';?>>Schedules</a></li>
        <li><a href="<?=$path?>News-Events/" <?php if ($myFileName == 'news_events.php') echo 'class="active"';?>>News & Events</a></li>
        <li><a href="<?=$path?>Gallery/" <?php if ($myFileName == 'gallery.php') echo 'class="active"';?>>Gallery</a></li>
        
        <li><a href="<?=$path?>Contact/" <?php if ($myFileName == 'contact.php') echo 'class="active"';?>>Contact</a></li>
    </ul>
</nav>
<div id="navHorizontalMeanMenuWrapper">&nbsp;
<nav id="navHorizontalMeanMenu">
    <ul class="menu" id="menu">
        <li id="homeNav"><a href="<?=$path?>">Home</a></li>
        <li><a href="<?=$path?>The-League/" <?php if ($myFileName == 'the_league.php') echo 'class="active"';?>>The League</a>
        	<ul>
                <li><a href="<?=$path?>The-Board/" >The Board</a></li>
                <li><a href="<?=$path?>Committees/" >Committees</a></li>
                <li><a href="<?=$path?>Registration/" >Registration</a></li>
            </ul>
        </li>
        <li><a href="<?=$path?>About-Bocce-Ball/" <?php if ($myFileName == 'about_bocce_ball.php') echo 'class="active"';?>>About Bocce Ball</a>
        	<ul>
                <li><a href="<?=$path?>Rules/" >Rules</a></li>
                <li><a href="<?=$path?>Bocce-Glossary/" >Glossary</a></li>
                <li><a href="<?=$path?>Frequently-Asked-Questions/" >FAQs</a></li>
                <li><a href="<?=$path?>Bocce-Resources/" >Bocce Resources</a></li>
            </ul>	
        </li>
        <li><a href="<?=$path?>Standings/" <?php if ($myFileName == 'standings.php') echo 'class="active"';?>>Standings</a>
        	<ul>
                <li><a href="<?=$path?>StandingsOverall/" >Overall Standings</a></li>                
            </ul>
        </li>
        <li><a href="<?=$path?>Schedules/" <?php if ($myFileName == 'schedules.php') echo 'class="active"';?>>Schedules</a></li>
        <li><a href="<?=$path?>News-Events/" <?php if ($myFileName == 'news_events.php') echo 'class="active"';?>>News & Events</a></li>
        <li><a href="<?=$path?>Gallery/" <?php if ($myFileName == 'gallery.php') echo 'class="active"';?>>Gallery</a></li>
        
        <li><a href="<?=$path?>Contact/" <?php if ($myFileName == 'contact.php') echo 'class="active"';?>>Contact</a></li>
    </ul>
</nav>
</div>
<script type="text/javascript" src="<?=$path?>scripts/meanmenu/jquery.meanmenu.js"></script>
<script>
jQuery(document).ready(function () {
    jQuery('header #navHorizontalMeanMenu').meanmenu({
										  meanMenuContainer: '#header',
										  meanScreenWidth: "780",
										  
										  });
});
</script>
<script>

	jQuery(document).ready(function() {
		jQuery('ul.sf-menu').superfish({
									   cssArrows:   false
									   });
	});

</script>