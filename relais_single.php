<?php

require_once ('jpgraph_php5/jpgraph.php');
require_once ('jpgraph_php5/jpgraph_line.php');
require_once ('jpgraph_php5/jpgraph_bar.php');
require_once ('jpgraph_php5/jpgraph_date.php');

function convertTimestamp($day, $month, $year, $hour, $minute, $second)
{
    $timestamp = $year;
    if($month < 10)
        $timestamp = $timestamp . "0" . $month;
    else
        $timestamp = $timestamp . $month;

    if($day < 10)
        $timestamp = $timestamp . "0" . $day;
    else
        $timestamp = $timestamp . $day;

    if($hour < 10)
        $timestamp = $timestamp . "0" . $hour;
    else
        $timestamp = $timestamp . $hour;

    if($minute < 10)
        $timestamp = $timestamp . "0" . $minute;
    else
        $timestamp = $timestamp . $minute;

    if($second < 10)
        $timestamp = $timestamp . "0" . $second;
    else
        $timestamp = $timestamp . $second;

    return (string)$timestamp;
}

if ($connection = mysqli_connect('localhost','heating','heating','heating')){
	
	$day_start = $_GET["daystart"];
	$month_start = $_GET["monthstart"];
	$year_start =  $_GET["yearstart"];
	$day_stop = $_GET["daystop"];
	$month_stop = $_GET["monthstop"];
	$year_stop =  $_GET["yearstop"];

	if ($day_start == 0 || $day_stop == 0){ 
		$day_start = 1; 
		$day_stop = 31;
	}
	if ($month_start == 0 || $month_stop == 0){
		$month_start = 1;
		$month_stop = 12;
	}
	if ($year_start == 0 || $year_stop == 0){
		$today = getdate();
		$year_start = $today['year'];
		$year_stop = $today['year'];
	}
	$begin = convertTimestamp($day_start, $month_start, $year_start, 0, 0, 0);
     	$end   = convertTimestamp($day_stop, $month_stop, $year_stop, 23, 59, 59);
	switch ($_GET["type"]){
		case 1:
  			$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat"
	   			." FROM deltasole WHERE timestamp >= $begin AND timestamp <= $end "
				."ORDER by timestamp ASC";
			break;
		case 2:
			$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat"
	   			." FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end "
				."ORDER by timestamp ASC";
			break;
		default:
			break;
	}
	$result = $connection->query($sql);
	$num = $result->num_rows;
	$texttickint = (integer) ($num % 40);
      if ($num > 500){
		$factor=(integer) ($num/500); 
	} else {
		$factor = 1;
	}

 		$graph = new Graph(1000,500, "auto");
	$maxy = 1.05;
	switch ($_GET["type"]){
		case 1:
			$maxy *= 100;
			break;
	}
 		$graph->SetScale("textlin", 0, $maxy);
	$graph->legend->SetAbsPos(5,5,'right','top');
	$graph->SetMargin(30,220,20,150);
 		$texttickint = 20;
 		$graph->xaxis->SetTextTickInterval($texttickint);
	$graph->xaxis->SetLabelAngle(90);
	$graph->xaxis->SetPos('min');
	switch ($_GET["type"]){
		case 1: 
				if ($myrow=$result->fetch_array()) {
    				do {
					if (($i % $factor) == 0){
						$solarpump[] = $myrow["solarpump"];
 						$buffer_relay[] = $myrow["buffer_relay"];
 						$ww_relay[] = $myrow["ww_relay"];
						$rl_relay[] = $myrow["rl_relay"];
						$heatingpump[] = $myrow["heatingpump"];
						$dates[] = $myrow["dateformat"]
          						. " " . $myrow["time"];
					}
					$i++;
    				}while ($myrow=$result->fetch_array());
 				}		
 				$graph->xaxis->SetTickLabels($dates);
 				$p1 = new LinePlot($solarpump);
 				$p1->SetColor('#0000FF'); 
 				$p1->SetLegend("Solar Pumpe");
 				$p2 = new LinePlot($buffer_relay);
 				$p2->SetLegend("Puffer Relais");
 				$p2->SetColor('#00FF00');
 				$p3 = new LinePlot($ww_relay);
 				$p3->SetLegend("Warmwasser Relais");
 				$p3->SetColor('#FF0000');
			$p4 = new LinePlot($rl_relay);
 				$p4->SetLegend("Ruecklauf Relais");
 				$p4->SetColor('#FF00FF');
			$p5 = new LinePlot($heatingpump);
 				$p5->SetLegend("Heizungspumpe");
 				$p5->SetColor('#FFFF00');
			$graph->Add($p1);
 				$graph->Add($p2);
 				$graph->Add($p3);
			$graph->Add($p4);
			$graph->Add($p5);
 				$graph->Stroke();
			break;
		case 2:
			if ($myrow=$result->fetch_array()) {
    				do {
					if (($i % $factor) == 0){
						$boiler[] = $myrow["boiler_state"];
						$pump_m1[] = $myrow["pump_M1"];
 						$pump_m2[] = $myrow["pump_M2"];
 						$pump_ww[] = $myrow["pump_ww"];
						$pump_circ[] = $myrow["pump_circ"];
						//$mixer[] = $myrow["mixer"];
						$dates[] = $myrow["dateformat"]
          						. " " . $myrow["time"];
					}
					$i++;
    				}while ($myrow=$result->fetch_array());
 				}		
 				$graph->xaxis->SetTickLabels($dates);
			$p1 = new LinePlot($boiler);
 				$p1->SetColor('blue'); 
 				$p1->SetLegend("Brenner");
 				$p2 = new LinePlot($pump_m1);
 				$p2->SetColor('green'); 
 				$p2->SetLegend("Heizungspumpe1");
 				$p3 = new LinePlot($pump_m2);
 				$p3->SetLegend("Heizungspumpe2");
 				$p3->SetColor('red');
 				$p4 = new LinePlot($pump_ww);
 				$p4->SetLegend("Warmwasserpumpe");
 				$p4->SetColor('orange');
			$p5 = new LinePlot($pump_circ);
 				$p5->SetLegend("Zirkulationspumpe");
 				$p5->SetColor('violet');
			/*$p6 = new LinePlot($mixer);
 				$p6->SetLegend("Mischer");
 				$p6->SetColor('lightblue');*/
			$graph->Add($p1);
 				$graph->Add($p2);
 				$graph->Add($p3);
			$graph->Add($p4);
			$graph->Add($p5);
			//$graph->Add($p6);
 				$graph->Stroke();

		default:
			break;
	}
}
?>
