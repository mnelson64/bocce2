<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=3;

// Ajax Stuff ///////////////////////////////////////////////////////////
if (isset($_GET['teamID'])) {
	
	$scores_query=sprintf("SELECT * FROM scores, games WHERE (`gameTeamA` = '%s' OR `gameTeamB` = '%s') AND `gameYear` = '%s' AND games.gameID = scores.scoreGame",mysql_real_escape_string($_GET['teamID']),mysql_real_escape_string($_GET['teamID']),$_SESSION['sYear']);	
	//echo $schedule_query;
	$scores_set = mysql_query($scores_query) or die(mysql_error());
	$row_scores_set = mysql_fetch_assoc($scores_set);
	echo '<div id="standingsScoreWrapper">';
	echo '<div id="test-popup" class="white-popup">';
	echo '<div id="standingsScoreHeading">'.$row_scores_set['gameYear'].' Scores for '.$teamArray[$_SESSION['sYear']][$_GET['teamID']]['teamName'].'</div>';
	
	do {
		$teamA = $teamArray[$_SESSION['sYear']][$row_scores_set['gameTeamA']]['teamName'];
		$teamB = $teamArray[$_SESSION['sYear']][$row_scores_set['gameTeamB']]['teamName'];
		
		echo '<div class="standingsScoreEntry">';
		echo '<div class="standingsScoreHeadingDate heading">'.date('M j', $row_scores_set['gameDate']).'</div>';
		echo '<div class="standingsScoreHeadingTeamA heading">'.$teamA.'</div>';
		echo '<div class="standingsScoreHeadingTeamB heading">'.$teamB.'</div>';
		echo '<div class="standingsScoreHeadingWinner heading">Winner</div>';
		
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
			echo '<div class="standingsScoreGameEntry background_lite">';
			echo '	<div class="standingsGameEntryGameNumber">Game 1</div>';
			echo '	<div class="standingsGameEntryTeamA '.$winner1a.'">'.$row_scores_set['score1a'].'</div>';
			echo '	<div class="standingsGameEntryTeamB '.$winner1b.'">'.$row_scores_set['score1b'].'</div>';
			echo '	<div class="standingsGameEntryWinner">'.$winner1.'</div>';
			echo '</div>';
			echo '<div class="standingsScoreGameEntry background_dark">';
			echo '	<div class="standingsGameEntryGameNumber">Game 2</div>';
			echo '	<div class="standingsGameEntryTeamA '.$winner2a.'">'.$row_scores_set['score2a'].'</div>';
			echo '	<div class="standingsGameEntryTeamB '.$winner2b.'">'.$row_scores_set['score2b'].'</div>';
			echo '	<div class="standingsGameEntryWinner">'.$winner2.'</div>';
			echo '</div>';
			echo '<div class="standingsScoreGameEntry background_lite">';
			echo '	<div class="standingsGameEntryGameNumber">Game 3</div>';
			echo '	<div class="standingsGameEntryTeamA '.$winner3a.'">'.$row_scores_set['score3a'].'</div>';
			echo '	<div class="standingsGameEntryTeamB '.$winner3b.'">'.$row_scores_set['score3b'].'</div>';
			echo '	<div class="standingsGameEntryWinner">'.$winner3.'</div>';
			echo '</div>';
		} else {
			echo 'No scores available for this match.';
		}
		echo '</div>';
	} while ($row_scores_set = mysql_fetch_assoc($scores_set));
	echo '</div>';
	echo '</div>';
	exit;
}
/////////////////////////////////////////////////////////////////////////
$content_query=sprintf("SELECT * FROM pages WHERE pageID = '%s'",$pageID);
$content_set = mysql_query($content_query) or die(mysql_error());
$row_content_set = mysql_fetch_assoc($content_set);

function compareWins($a, $b) {
	if ($a['w'] == $b['w']) {
		if ($a['pf'] == $b['pf']) {
			if ($a['pa'] != $b['pa']) {
				return ($a['pa'] < $b['pa']) ? -1 : 1;
			} else {
				return 0;
			}
		} else {
			return ($a['pf'] > $b['pf']) ? -1 : 1;
		}
	}
	return ($a['w'] > $b['w']) ? -1 : 1;
}

if (!isset($_SESSION['sYear'])) {
	$_SESSION['sYear'] = date('Y');
} elseif (isset($_GET['year'])) {
	$_SESSION['sYear'] = $_GET['year'];
}

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
<link rel="stylesheet" href="<?=$path?>scripts/lightbox/dist/magnific-popup.css"> 
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
            <?php if ($_SESSION['sYear'] > 2013) { ?>
            <?=stripslashes($row_content_set['pageContent'])?>
            <?php } ?>
            <div id="standingsSelector">
            <label>Year</label>
            <select name="gameYear" id="gameYear" onChange="window.location =this.options[this.selectedIndex].value">
             <?php 
          	for ($y=date('Y'); $y >= 2011; $y--) { ?>
             <option value="<?=$path?>Standings/<?=$y?>/" <?php if ($_SESSION['sYear'] == $y) echo "selected=\"selected\"" ?>><?=$y?></option>
             <?php } ?>
             </select>
             </div>
            <div id="standingsLegend"><strong>PF</strong> = Points For    <br /><strong>PA</strong> = Points Against</div>
            <div id="standingsArea">
            <?php if ($_SESSION['sYear'] > 2013) { ?>
            <?php //print_r($teamArray);?>
            <?php foreach ($nightArray as $night) {?>
            	<div class="standingsNight">
                    <div class="standingsHeading" id="standingsHeading_<?=$night?>"><?=$night?> Night</div>
                    <div class="standingsBody" id="standingsBody_<?=$night?>">
                    <?php
					$teamScoresArray = array();
					// get teams for this night
					$team_query=sprintf("SELECT * FROM teams WHERE `teamYear` = '%s' AND `teamNight` = '%s'",mysql_real_escape_string($_SESSION['sYear']),$night);
					$team_set = mysql_query($team_query) or die(mysql_error());
					$row_team_set = mysql_fetch_assoc($team_set);
					do {
						$teamWins = 0;
						$gamesPlayed = 0;
						$pointsAgainst = 0;
						$pointsFor = 0;
						//echo $teamArray[$row_team_set['teamID']]['teamName'].'<br />';
						//get games
						$scores_query=sprintf("SELECT * FROM games,scores WHERE games.gameID = scores.scoreGame AND (gameTeamA = '%s' OR gameTeamB = '%s')",$row_team_set['teamID'],$row_team_set['teamID']);
						$scores_set = mysql_query($scores_query) or die(mysql_error());
						$row_scores_set = mysql_fetch_assoc($scores_set);
						if (mysql_num_rows($scores_set) > 0) {
							do {
								
								//print_r($row_scores_set);
								if ($row_scores_set['gameTeamA'] == $row_team_set['teamID']) {
									if ($row_scores_set['score1a'] == 12 or $row_scores_set['score1b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score1a'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score1b'];
										if ($row_scores_set['score1a'] == 12) {
											$teamWins++;
										}
										
									}
									if ($row_scores_set['score2a'] == 12 or $row_scores_set['score2b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score2a'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score2b'];
										if ($row_scores_set['score2a'] == 12) {
											$teamWins++;
										}
										
									}
									if ($row_scores_set['score3a'] == 12 or $row_scores_set['score3b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score3a'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score3b'];
										if ($row_scores_set['score3a'] == 12) {
											$teamWins++;
										}
										
									}
									
								} else {
									if ($row_scores_set['score1a'] == 12 or $row_scores_set['score1b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score1b'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score1a'];
										if ($row_scores_set['score1b'] == 12) {
											$teamWins++;
										}
										
									}
									if ($row_scores_set['score2a'] == 12 or $row_scores_set['score2b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score2b'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score2a'];
										if ($row_scores_set['score2b'] == 12) {
											$teamWins++;
										}
										
									}
									if ($row_scores_set['score3a'] == 12 or $row_scores_set['score3b'] == 12) {
										$gamesPlayed++;
										$pointsFor = $pointsFor + $row_scores_set['score3b'];
										$pointsAgainst = $pointsAgainst + $row_scores_set['score3a'];
										if ($row_scores_set['score3b'] == 12) {
											$teamWins++;
										}
										
									}
								}
								
								
							} while ($row_scores_set = mysql_fetch_assoc($scores_set));
							//store in array
							$teamScoresArray[$row_team_set['teamID']] = array ('gp' => $gamesPlayed, 'w' => $teamWins, 'l' => ($gamesPlayed - $teamWins), 'pf' => $pointsFor, 'pa' => $pointsAgainst);
							//print_r($teamScoresArray[$row_team_set['teamID']]);
						}
						
						
					} while ($row_team_set = mysql_fetch_assoc($team_set));
					// order by wins, the points
					// Comparison function
					
					
					// Sort and print the resulting array
					uasort($teamScoresArray, 'compareWins');
                    //print_r($teamScoresArray);
					
					?>
                            <div class="standingsEntryHeading">
                                <div class="standingsEntryHeadingTeam standingsEntryMargin">Team</div>
                                <div class="standingsEntryHeadingGP standingsEntryMargin">GP</div>
                                <div class="standingsEntryHeadingWins standingsEntryMargin">Win</div>
                                <div class="standingsEntryHeadingLosses standingsEntryMargin">Loss</div>
                                <div class="standingsEntryHeadingPF standingsEntryMargin">PF</div>
                                <div class="standingsEntryHeadingPA standingsEntryMargin">PA</div>
                            </div>
                        <?php $idx = 0;
							foreach ($teamScoresArray as $teamID => $teamStanding) {?>
                            <div class="standingsEntry <?php if ($idx == 0) { echo ' background_lite'; } else { echo ' background_dark'; }?>" >
                                <div class="standingsEntryTeam standingsEntryMargin"><a href="<?=$path?>Standings/scores/<?=$teamID?>/" class="lightbox"><?=$teamArray[$_SESSION['sYear']][$teamID]['teamName']?></a></div>
                                <div class="standingsEntryGP standingsEntryMargin"><?=$teamStanding['gp']?></div>
                                <div class="standingsEntryWins standingsEntryMargin"><?=$teamStanding['w']?></div>
                                <div class="standingsEntryLosses standingsEntryMargin"><?=$teamStanding['l']?></div>
                                <div class="standingsEntryPF standingsEntryMargin"><?=$teamStanding['pf']?></div>
                                <div class="standingsEntryPA standingsEntryMargin"><?=$teamStanding['pa']?></div>
                            </div>
                        <?php $idx = ($idx == 0) ? 1:0;
						}?>
                    </div>
                 </div>
            <?php } ?>
            <?php } elseif ($_SESSION['sYear'] == 2013) {?>
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0"><colgroup> <col width="20%" /> <col width="10%" /> <col width="10%" /> <col width="10%" /> <col width="10%" /> <col width="10%" /></colgroup>
                <tbody>
                <tr>
                <td><strong>TEAM</strong></td>
                <td><strong>W</strong></td>
                <td><strong>L</strong></td>
                <td><strong>P</strong></td>
                <td><strong>G</strong></td>
                </tr>
                <tr>
                <td height="22"><strong>Mon</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Rolling Stones</td>
                <td>47</td>
                <td>16</td>
                <td>672</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Casino Royale</td>
                <td>41</td>
                <td>22</td>
                <td>643</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Rock N Rollers</td>
                <td>40</td>
                <td>23</td>
                <td>643</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Ragazze</td>
                <td>40</td>
                <td>23</td>
                <td>623</td>
                <td>63</td>
                </tr>
                <tr>
                <td>High Rollers</td>
                <td>28</td>
                <td>35</td>
                <td>555</td>
                <td>63</td>
                </tr>
                <tr>
                <td>West Side Rollers</td>
                <td>25</td>
                <td>38</td>
                <td>535</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Balls of Fire</td>
                <td>19</td>
                <td>44</td>
                <td>502</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Amici</td>
                <td>15</td>
                <td>48</td>
                <td>416</td>
                <td>63</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td height="22"><strong>Tues</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Hot Shots</td>
                <td>44</td>
                <td>19</td>
                <td>661</td>
                <td>63</td>
                </tr>
                <tr>
                <td>WCR Screwballs</td>
                <td>40</td>
                <td>23</td>
                <td>645</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Zinners</td>
                <td>35</td>
                <td>28</td>
                <td>600</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Classics</td>
                <td>35</td>
                <td>28</td>
                <td>588</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Money Ball</td>
                <td>31</td>
                <td>32</td>
                <td>587</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Glencannon Ballers</td>
                <td>28</td>
                <td>35</td>
                <td>570</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bonnie Ball Bangers</td>
                <td>27</td>
                <td>36</td>
                <td>521</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocceme Mucho</td>
                <td>12</td>
                <td>51</td>
                <td>434</td>
                <td>63</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td height="22"><strong>Wed</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Sunce</td>
                <td>49</td>
                <td>14</td>
                <td>694</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Harvest Moon</td>
                <td>39</td>
                <td>24</td>
                <td>620</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Boccelism</td>
                <td>34</td>
                <td>29</td>
                <td>607</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce on a Roll</td>
                <td>33</td>
                <td>30</td>
                <td>599</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Collectiballs</td>
                <td>32</td>
                <td>31</td>
                <td>590</td>
                <td>63</td>
                </tr>
                <tr>
                <td>La Bocce Vita</td>
                <td>27</td>
                <td>36</td>
                <td>574</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Untouchaballs</td>
                <td>21</td>
                <td>42</td>
                <td>520</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Rockets</td>
                <td>17</td>
                <td>46</td>
                <td>431</td>
                <td>63</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td height="22"><strong>Thur</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Pace</td>
                <td>46</td>
                <td>17</td>
                <td>686</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Pazzi Pallinos</td>
                <td>44</td>
                <td>19</td>
                <td>667</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Crazy Eights</td>
                <td>33</td>
                <td>29</td>
                <td>607</td>
                <td>62</td>
                </tr>
                <tr>
                <td>De Boccery</td>
                <td>30</td>
                <td>33</td>
                <td>604</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Wine Country Roofing</td>
                <td>30</td>
                <td>33</td>
                <td>577</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Squadra Italiana Tre</td>
                <td>27</td>
                <td>36</td>
                <td>578</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Kelly's Appliance</td>
                <td>21</td>
                <td>41</td>
                <td>500</td>
                <td>62</td>
                </tr>
                <tr>
                <td>Bocce Boomers</td>
                <td>20</td>
                <td>43</td>
                <td>516</td>
                <td>63</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td height="22"><strong>Fri</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>West Count Wobblers</td>
                <td>42</td>
                <td>18</td>
                <td>630</td>
                <td>60</td>
                </tr>
                <tr>
                <td>Juiliard Court Jesters</td>
                <td>35</td>
                <td>25</td>
                <td>588</td>
                <td>60</td>
                </tr>
                <tr>
                <td>Just Bocce'n</td>
                <td>25</td>
                <td>35</td>
                <td>516</td>
                <td>60</td>
                </tr>
                <tr>
                <td>Renegades</td>
                <td>18</td>
                <td>12</td>
                <td>306</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Fantasiballs</td>
                <td>16</td>
                <td>44</td>
                <td>429</td>
                <td>60</td>
                </tr>
                <tr>
                <td>Pros &amp; Cons</td>
                <td>14</td>
                <td>16</td>
                <td>266</td>
                <td>30</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>
            <?php } elseif ($_SESSION['sYear'] == 2012) {?>
            	<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                <td width="20%"><strong>TEAM</strong></td>
                <td width="10%"><strong>W</strong></td>
                <td width="10%"><strong>L</strong></td>
                <td width="10%"><strong>P</strong></td>
                <td width="10%"><strong>G</strong></td>
                </tr>
                <tr>
                <td><strong>Mon&nbsp;</strong></td>
                <td><strong>&nbsp;</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>&nbsp;</strong></td>
                </tr>
                <tr>
                <td>Rolling Stones</td>
                <td>53</td>
                <td>10</td>
                <td>717</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Casino Royale</td>
                <td>43</td>
                <td>20</td>
                <td>650</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Rock N Rollers</td>
                <td>34</td>
                <td>29</td>
                <td>618</td>
                <td>63</td>
                </tr>
                <tr>
                <td>West Side Rollers</td>
                <td>33</td>
                <td>30</td>
                <td>581</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Balls of Fire</td>
                <td>29</td>
                <td>34</td>
                <td>558</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Ragazze</td>
                <td>26</td>
                <td>37</td>
                <td>525</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Ready To Roll</td>
                <td>18</td>
                <td>45</td>
                <td>486</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce Amici</td>
                <td>15</td>
                <td>48</td>
                <td>460</td>
                <td>63</td>
                </tr>
                <tr>
                <td><strong>&nbsp;</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><strong><strong>Tues&nbsp;</strong></strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Screwballs</td>
                <td>20</td>
                <td>10</td>
                <td>304</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Hot Shots</td>
                <td>18</td>
                <td>12</td>
                <td>296</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Bocce Zinners</td>
                <td>17</td>
                <td>13</td>
                <td>288</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Glencannon Ballers</td>
                <td>15</td>
                <td>15</td>
                <td>268</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Rincon Rebels</td>
                <td>12</td>
                <td>18</td>
                <td>270</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Eccob</td>
                <td>9</td>
                <td>21</td>
                <td>241</td>
                <td>30</td>
                </tr>
                <tr>
                <td><strong>&nbsp;</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><strong>Wed&nbsp;</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Sunce</td>
                <td>51</td>
                <td>12</td>
                <td>699</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Harvest Moon</td>
                <td>41</td>
                <td>22</td>
                <td>662</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Bocce on a Roll</td>
                <td>35</td>
                <td>28</td>
                <td>632</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Collectiballs</td>
                <td>35</td>
                <td>28</td>
                <td>621</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Boccelism</td>
                <td>35</td>
                <td>28</td>
                <td>584</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Untouchaballs</td>
                <td>22</td>
                <td>41</td>
                <td>527</td>
                <td>63</td>
                </tr>
                <tr>
                <td>La Bocce Vita</td>
                <td>22</td>
                <td>41</td>
                <td>526</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Rockets</td>
                <td>11</td>
                <td>52</td>
                <td>428</td>
                <td>63</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><strong>Thur&nbsp;</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Wine Country Roofing</td>
                <td>48</td>
                <td>15</td>
                <td>657</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Pazzi Pallinos</td>
                <td>40</td>
                <td>23</td>
                <td>602</td>
                <td>63</td>
                </tr>
                <tr>
                <td>High Rollers</td>
                <td>37</td>
                <td>26</td>
                <td>603</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Crazy Eights</td>
                <td>35</td>
                <td>28</td>
                <td>635</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Pace</td>
                <td>33</td>
                <td>30</td>
                <td>590</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Squadra Italiana Tre</td>
                <td>27</td>
                <td>36</td>
                <td>554</td>
                <td>63</td>
                </tr>
                <tr>
                <td>Wallbangers</td>
                <td>24</td>
                <td>39</td>
                <td>469</td>
                <td>63</td>
                </tr>
                <tr>
                <td>De Boccery</td>
                <td>11</td>
                <td>52</td>
                <td>425</td>
                <td>63</td>
                </tr>
                </tbody>
                </table>
            <?php } elseif ($_SESSION['sYear'] == 2011) {?>
				<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                <td width="20%"><strong>TEAM</strong></td>
                <td width="10%"><strong>W</strong></td>
                <td width="10%"><strong>L</strong></td>
                <td width="10%"><strong>P</strong></td>
                <td width="10%"><strong>G</strong></td>
                </tr>
                <tr>
                <td><strong>Tues</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Casino Royale</td>
                <td>21</td>
                <td>9</td>
                <td>319</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Bocce Ragazze</td>
                <td>19</td>
                <td>11</td>
                <td>294</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Balls of Fire</td>
                <td>15</td>
                <td>15</td>
                <td>277</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Boccezze</td>
                <td>5</td>
                <td>25</td>
                <td>192</td>
                <td>30</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><strong>Wed</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Sunce</td>
                <td>27</td>
                <td>3</td>
                <td>349</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Bocce on a Roll</td>
                <td>18</td>
                <td>12</td>
                <td>317</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Squadra Italiana Due</td>
                <td>18</td>
                <td>12</td>
                <td>268</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Harvest Moon</td>
                <td>16</td>
                <td>14</td>
                <td>262</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Collectiballs</td>
                <td>13</td>
                <td>17</td>
                <td>248</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Boccelism</td>
                <td>12</td>
                <td>17</td>
                <td>267</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Rockets</td>
                <td>8</td>
                <td>22</td>
                <td>235</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Squadra Italiana Uno</td>
                <td>8</td>
                <td>22</td>
                <td>230</td>
                <td>30</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><strong>Thur</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>High Rollers</td>
                <td>25</td>
                <td>5</td>
                <td>333</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Rolling Stones</td>
                <td>23</td>
                <td>7</td>
                <td>334</td>
                <td>30</td>
                </tr>
                <tr>
                <td>*Crazy Eights</td>
                <td>22</td>
                <td>7</td>
                <td>328</td>
                <td>*29</td>
                </tr>
                <tr>
                <td>*Wallbangers II</td>
                <td>19</td>
                <td>10</td>
                <td>294</td>
                <td>*29</td>
                </tr>
                <tr>
                <td>St Eugene's ICF</td>
                <td>12</td>
                <td>18</td>
                <td>261</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Pazzi Pallinos</td>
                <td>8</td>
                <td>22</td>
                <td>226</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Squadra Italiana Tre</td>
                <td>7</td>
                <td>23</td>
                <td>197</td>
                <td>30</td>
                </tr>
                <tr>
                <td>Bocce Amici</td>
                <td>3</td>
                <td>17</td>
                <td>166</td>
                <td>30</td>
                </tr>
                </tbody>
                </table>
			<?php } ?>
            </div>
            <div id="uplink"><a href="#header"><img src="<?=$path?>images/up_arrow.png"  alt="Back to Top"></a></div>
        </div>
        <div id="subRight">
        <?php include "sideBoxRightAds.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
<script src="<?=$path?>scripts/lightbox/dist/jquery.magnific-popup.js"></script>
<script type="text/javascript">
<!--
$('.lightbox').magnificPopup({
  type: 'ajax'
});
-->
</script>
</body>
</html>