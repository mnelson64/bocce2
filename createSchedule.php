<?php 
include "application_top.php";

$schedule_query=sprintf("SELECT * FROM games WHERE `gameNight` = '%s' AND `gameYear` = '%s' ORDER BY `gameDate`",$_GET['night'],date('Y'));	
//echo $schedule_query;
$schedule_set = mysql_query($schedule_query) or die(mysql_error());
$row_schedule_set = mysql_fetch_assoc($schedule_set);


include 'pdf/class.ezpdf.php';

$pdf = new Cezpdf();
$pdf -> ezSetMargins(20,20,20,20);
$pdf->setStrokeColor(0,0,0,1);
$pdf->selectFont('pdf/fonts/Times-Roman.afm');

do {
	$data[] = array('Date' => date('M j' ,$row_schedule_set['gameDate']),'Team A' => $teamArray[date('Y')][$row_schedule_set['gameTeamA']]['teamName'], 'Team B' => $teamArray[date('Y')][$row_schedule_set['gameTeamB']]['teamName'], 'Court' => $row_schedule_set['gameCourt']);
} while ($row_schedule_set = mysql_fetch_assoc($schedule_set));

$pdf->ezTable($data,'',date('Y').' '.$_GET['night'].' Night Schedule',array('width' => 500));

/*$certificateFile = "schedule.pdf";
$handle = fopen("PDFschedules/".$certificateFile, "w+");
$fileOutput = $pdf->output();
fwrite($handle,$fileOutput);
fclose($handle);*/
$pdf->ezStream();

?>