<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=4;

// Ajax Stuff ////////////////////////////////////////////////////////////

if (isset($_GET['gameID'])) {
	$scores_query=sprintf("SELECT * FROM scores WHERE `scoreGame` = '%s'",mysql_real_escape_string($_GET['gameID']));	
	//echo $schedule_query;
	$scores_set = mysql_query($scores_query) or die(mysql_error());
	$row_scores_set = mysql_fetch_assoc($scores_set);
	
	$game_query=sprintf("SELECT * FROM games WHERE `gameID` = '%s'",mysql_real_escape_string($_GET['gameID']));	
	//echo $schedule_query;
	$game_set = mysql_query($game_query) or die(mysql_error());
	$row_game_set = mysql_fetch_assoc($game_set);
	
	$teamA = $teamArray[date('Y')][$row_game_set['gameTeamA']]['teamName'];
	$teamB = $teamArray[date('Y')][$row_game_set['gameTeamB']]['teamName'];
	
	$winner1a = ($row_scores_set['score1a'] == 12 ? 'winner' : '');
	$winner1b = ($row_scores_set['score1b'] == 12 ? 'winner' : '');
	$winner2a = ($row_scores_set['score2a'] == 12 ? 'winner' : '');
	$winner2b = ($row_scores_set['score2b'] == 12 ? 'winner' : '');
	$winner3a = ($row_scores_set['score3a'] == 12 ? 'winner' : '');
	$winner3b = ($row_scores_set['score3b'] == 12 ? 'winner' : '');
	
	if($row_scores_set['score1a'] != $row_scores_set['score1b']) {
		$winner1 = ($row_scores_set['score1a'] > $row_scores_set['score1b'] ? $teamA : $teamB);
	} else {
		$winner1 = '';
	}
	
	if($row_scores_set['score2a'] != $row_scores_set['score2b']) {
		$winner2 = ($row_scores_set['score2a'] > $row_scores_set['score2b'] ? $teamA : $teamB);
	} else {
		$winner2 = '';
	}
	
	if($row_scores_set['score3a'] != $row_scores_set['score3b']) {
		$winner3 = ($row_scores_set['score3a'] > $row_scores_set['score3b'] ? $teamA : $teamB);
	} else {
		$winner3 = '';
	}
	
	if (mysql_num_rows($scores_set)) {
		echo '<div class="scheduleScoreEntry background_lite">';
		echo '	<div class="scheduleGameEntryGameNumber scheduleGameEntryClass">Game 1</div>';
		echo '	<div class="scheduleGameEntryTeamA '.$winner1a.' scheduleGameEntryClass">'.$row_scores_set['score1a'].'</div>';
		echo '	<div class="scheduleGameEntryTeamB '.$winner1b.' scheduleGameEntryClass">'.$row_scores_set['score1b'].'</div>';
		//echo '	<div class="scheduleGameEntryCourt scheduleGameEntryClass">'.$winner1.'</div>';
		echo '	<div class="scheduleGameEntryScores scheduleGameEntryClass">&nbsp;</div>';
		echo '</div>';
		echo '<div class="scheduleScoreEntry background_dark">';
		echo '	<div class="scheduleGameEntryGameNumber scheduleGameEntryClass">Game 2</div>';
		echo '	<div class="scheduleGameEntryTeamA '.$winner2a.' scheduleGameEntryClass">'.$row_scores_set['score2a'].'</div>';
		echo '	<div class="scheduleGameEntryTeamB '.$winner2b.' scheduleGameEntryClass">'.$row_scores_set['score2b'].'</div>';
		//echo '	<div class="scheduleGameEntryCourt scheduleGameEntryClass">'.$winner2.'</div>';
		echo '	<div class="scheduleGameEntryScores scheduleGameEntryClass">&nbsp;</div>';
		echo '</div>';
		echo '<div class="scheduleScoreEntry background_lite">';
		echo '	<div class="scheduleGameEntryGameNumber scheduleGameEntryClass">Game 3</div>';
		echo '	<div class="scheduleGameEntryTeamA '.$winner3a.' scheduleGameEntryClass">'.$row_scores_set['score3a'].'</div>';
		echo '	<div class="scheduleGameEntryTeamB '.$winner3b.' scheduleGameEntryClass">'.$row_scores_set['score3b'].'</div>';
		//echo '	<div class="scheduleGameEntryCourt scheduleGameEntryClass">'.$winner3.'</div>';
		echo '	<div class="scheduleGameEntryScores scheduleGameEntryClass">&nbsp;</div>';
		echo '</div>';
	} else {
		echo 'No scores available for this match.';
	}
	exit;
}
//////////////////////////////////////////////////////////////////////////
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
            <div id="scheduleArea">
            <?php //print_r($teamArray);?>
            <?php foreach ($nightArray as $night) {
				$schedule_query=sprintf("SELECT * FROM games WHERE `gameNight` = '%s' AND `gameYear` = '%s' ORDER BY `gameDate`",$night,date('Y'));	
				//echo $schedule_query;
				$schedule_set = mysql_query($schedule_query) or die(mysql_error());
				$row_schedule_set = mysql_fetch_assoc($schedule_set);
				if ($night == $_COOKIE['night']) {
					$showSchedule = 'scheduleBodyOpen';
				} else {
					$showSchedule = '';
				}?>
            	
            	<div class="scheduleNight">
                    <div class="scheduleHeading" id="scheduleHeading_<?=$night?>"><a href="javascript: toggleSchedule('<?=$night?>');"><?=$night?> Night</a></div>
                    <?php if (mysql_num_rows($schedule_set) > 0) {?>
                    <div class="schedulePDF"><a href="<?=$path?>createSchedule.php?night=<?=$night?>" target="_blank"><img src="<?=$path?>images/PDF-Icon.jpg"></a></div>
                    <?php } ?>
                  <div class="scheduleBody <?=$showSchedule?>" id="scheduleBody_<?=$night?>">
            <?php if (mysql_num_rows($schedule_set) > 0) {?>
                            <div class="scheduleGameEntryHeading">
                                <div class="scheduleGameEntryHeadingDate scheduleGameEntryClass">Date</div>
                                <div class="scheduleGameEntryHeadingTeamA scheduleGameEntryClass">Team A</div>
                                <div class="scheduleGameEntryHeadingTeamB scheduleGameEntryClass">Team B</div>
                                <div class="scheduleGameEntryHeadingCourt scheduleGameEntryClass">Court</div>
                                <div class="scheduleGameEntryHeadingScores scheduleGameEntryClass">Scores</div>
                            </div>
                        <?php $lastDate = '';;
							do {?>
                            <?php if ($row_schedule_set['gameDate'] != $lastDate and $lastDate != '') { ?>
                            <div class="scheduleGameEntrySeparator"></div>
                            <?php } ?>
                            <div class="scheduleGameEntry">
                                <div class="scheduleGameEntryDate scheduleGameEntryClass"><?=date('M j' ,$row_schedule_set['gameDate'])?></div>
                                <div class="scheduleGameEntryTeamA scheduleGameEntryClass"><?=$teamArray[date('Y')][$row_schedule_set['gameTeamA']]['teamName']?></div>
                                <div class="scheduleGameEntryTeamB scheduleGameEntryClass"><?=$teamArray[date('Y')][$row_schedule_set['gameTeamB']]['teamName']?></div>
                                <div class="scheduleGameEntryCourt scheduleGameEntryClass"><?=$row_schedule_set['gameCourt']?></div>
                                <div class="scheduleGameEntryScores scheduleGameEntryClass"><a href="javascript:toggleScores('<?=$row_schedule_set['gameID']?>')"><img src="<?=$path?>config/images/Doc.png" title="View Scores" alt="Scores"></a></div>
                                <div class="scheduleGameEntryScoreDisplay" id="scheduleGame_<?=$row_schedule_set['gameID']?>">
                                </div>
                            </div>
                            
                        <?php $lastDate =  $row_schedule_set['gameDate'];
							} while ($row_schedule_set = mysql_fetch_assoc($schedule_set));?>
                        <div class="scheduleGameEntryLink"><a href="#scheduleHeading_<?=$night?>">Back to Top ^</a></div>
                    <?php
                    } else {
                        echo "No games scheduled";
                    }?>
                    </div>
                 </div>
            <?php } ?>
            </div>
            <div id="uplink"><a href="#header"><img src="<?=$path?>images/up_arrow.png"  alt="Back to Top"></a></div>
        </div>
        <div id="subRight">
        <?php include "sideBoxRightAds.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
<script type="text/javascript">
<!--
function toggleSchedule (night) {
	document.cookie = 'night='+night ;
	$("#scheduleBody_"+night).slideToggle(200);
}

function toggleScores (gameID) {
	$("#scheduleGame_"+gameID).load("<?=$path?>Schedules/scores/"+gameID+"/", function() {
  $("#scheduleGame_"+gameID).slideToggle(200);
});
	
	
}
-->
</script>
</body>
</html>