<?php

require_once ('jpgraph_php5/jpgraph.php');
require_once ('jpgraph_php5/jpgraph_line.php');
require_once ('jpgraph_php5/jpgraph_bar.php');

function getLastDayOfMonth($month, $year)
{
	return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}

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

if ($connection = mysql_connect('localhost','heating','heating')){
	if(mysql_select_db('heating',$connection)){
		$interval = $_GET["interval"];
		$day_start = $_GET["daystart"];
		$month_start = $_GET["monthstart"];
		$year_start =  $_GET["yearstart"];
		$day_stop = $_GET["daystop"];
		$month_stop = $_GET["monthstop"];
		$year_stop =  $_GET["yearstop"];

		if ($day_start == 0){ 
			$day_start = 1; 
			$day_stop = 31;
		}
		if ($month_start == 0){
			$month_start = 1;
			$month_stop = 12;
		}
		if ($year_start == 0){
			$today = getdate();
			$year_start = $today['year'];
			$year_stop = $today['year'];
		}
		if ($day_stop == 0) $day_stop = $day_start;
		if ($month_stop == 0) $month_stop = $month_start;
		if ($year_stop == 0) $year_stop = $year_start;

   		$graph = new Graph(1000,500, "auto");
   		$graph->SetScale("textlin");
		$graph->legend->SetAbsPos(5,5,'right','top');
		$graph->SetMargin(50,200,20,30);
   		$graph->xaxis->SetTextTickInterval(1);
		switch ($interval){
			case 2: 
					$begin = convertTimestamp(1, $month_start, $year_start, 0, 0, 0);
 					$end   = convertTimestamp(getLastDayOfMonth($month_stop, $year_stop), $month_stop, $year_stop, 23, 59, 59);
    				$sql = "SELECT energy, DATE_FORMAT( `date`, '%d.%m') AS timeformat"
					   ." FROM photovoltaik WHERE date >= $begin AND date"
					   ." <= $end ORDER by date";
					$result = mysql_query($sql);
					while ($myrow=mysql_fetch_array($result)) {
						$energy[] = $myrow["energy"];
						$dates[] = $myrow["timeformat"];
   					}
				if ($energy[0]){
   					$graph->xaxis->SetTickLabels($dates);
   					$p1 = new BarPlot($energy);
   					$p1->SetColor('#0000FF'); 
   					$p1->SetLegend("Energie Photovoltaik(kW/h)");
					$p1->SetValuePos('top');
					$p1->value->Show();
					$p1->SetFillColor('green');
					$group = new GroupBarPlot(array($p1));
					$graph->Add($group);
					$graph->Stroke();
				}
				break;
			case 3:  
    			for ($i = $month_start; $i <= $month_stop; $i++){	
					$begin = convertTimestamp(1, $i, $year_start, 0, 0, 0);
					$end   = convertTimestamp(getLastDayOfMonth($i,$year_stop), 
						$i, $year_stop, 23, 59, 59);
					$sql = "SELECT energy"
					   ." FROM photovoltaik WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_max = $myrow["energy"];
   					}
    				$sql = "SELECT energy, DATE_FORMAT( `date`, '%m.%Y') AS timeformat"
					   ." FROM photovoltaik WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_min = $myrow["energy"];
						$date = $myrow["timeformat"];
   					} else {
						$date = 0;
					}
					if ($date && ($energy_max - $energy_min || 
								$boilerhours_max - $boilerhours_min)){
						$energy_solar[] = max(($energy_max - $energy_min) / 1000, 0.0001);
						$energy_oil[] = max(($boilerhours_max - $boilerhours_min) * 24, 0.0001);
						$energy_wood_top[] = $wood_top;
						$energy_wood_bottom[] = $wood_bottom;
						$energy_total[] = 
								(($energy_max - $energy_min) / 1000) +
								(($boilerhours_max - $boilerhours_min) * 24) + 
								$wood_top + $wood_bottom;
						$dates[] = $date;
					}
   				}
			 	/*	
				for ($i = 0; $i < count($dates); $i++){
					echo $energy_solar[$i] . " " . $energy_oil[$i] . " " . 
						$energy_wood_top[$i] . " " . $energy_wood_bottom[$i] . " " . 
						$dates[$i] . "<br>";
				}
				*/
				if ($energy_solar[0]){
   					$graph->xaxis->SetTickLabels($dates);
   					$p1 = new BarPlot($energy_solar);
   					$p1->SetColor('green'); 
   					$p1->SetLegend("Energie Solar(kW/h)");
					$p1->SetValuePos('top');
					$p1->value->Show();
					$p1->SetFillColor('green');
					$p2 = new BarPlot($energy_oil);
   					$p2->SetColor('red'); 
   					$p2->SetLegend("Energie Oel(kW/h)");
					$p2->SetValuePos('top');
					$p2->value->Show();
					$p2->SetFillColor('red');
					$p3 = new BarPlot($energy_wood_top);
   					$p3->SetColor('black'); 
   					$p3->SetLegend("Energie Holz Oben(kW/h)");
					$p3->SetValuePos('top');
					$p3->value->Show();
					$p3->SetFillColor('black');
					$p4 = new BarPlot($energy_wood_bottom);
   					$p4->SetColor('brown'); 
   					$p4->SetLegend("Energie Holz Unten(kW/h)");
					$p4->SetValuePos('top');
					$p4->value->Show();
					$p4->SetFillColor('brown');
					$p5 = new BarPlot($energy_total);
   					$p5->SetColor('orange'); 
   					$p5->SetLegend("Energie Gesamt(kW/h)");
					$p5->SetValuePos('top');
					$p5->value->Show();
					$p5->SetFillColor('orange');


					$group = new GroupBarPlot(array($p1,$p2,$p3,$p4,$p5));
					$graph->Add($group);
					$graph->Stroke();
				}
				break;
			default:
				break;
		}
	}
}
?>
