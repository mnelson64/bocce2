<?php
include "admin_app_top.php";
include "checkUser.php";
include "defsGame.php"; 

require_once '../include/xls_reader/excel_reader2.php';
//$data = new Spreadsheet_Excel_Reader("../zips/friday_2014_p2.xls");
//$scheduleArray = array('Monday' => '../zips/monday_2016.xls','Tuesday' => '../zips/tuesday_2016.xls','Wednesday' => '../zips/wednesday_2016.xls','Thursday' => '../zips/thursday_2016.xls','Friday' => '../zips/friday_2016.xls');
$scheduleArray = array('Monday' => '../zips/monday_2016.xls');
$pass=1;
foreach ($scheduleArray as $gameNight => $schedule) {
	$data = new Spreadsheet_Excel_Reader($schedule);
	$row = 2;
	$column = 1;
	$gameYear = 2016;
	$gameDate = 0;
	$i=1;
	echo 'pass'.$pass;
	do {
		foreach ($teamArray[$gameYear] as $teamID => $team) {
			if ($team['teamName'] == trim($data->val($row,4))) {
				$teamA = $teamID;
			}
			if ($team['teamName'] == trim($data->val($row,5))) {
				$teamB = $teamID;
			}
		}
		// date
		if ($data->val($row,2) != '-') {
			$gameDate = strtotime($data->val($row,2));
		}
		
		$query = sprintf("INSERT INTO %s (gameYear,gameNight,gameDate,gameCourt,gameTeamA,gameTeamB) VALUES('%s','%s','%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($gameYear),mysql_real_escape_string($gameNight),$gameDate,mysql_real_escape_string($data->val($row,3)),mysql_real_escape_string($teamA),mysql_real_escape_string($teamB));	
		echo $query.' i='.$i.'<br />';
		//exit;
		mysql_query($query);
		echo mysql_error();
		$row++;
		$i++;
	} while ($data->val($row,1) != '');
	$pass++;
}
echo "Done!";
exit;
?>
</body>
</html>
