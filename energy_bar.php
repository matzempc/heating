<?php

require_once ('jpgraph_php5/jpgraph.php');
require_once ('jpgraph_php5/jpgraph_line.php');
require_once ('jpgraph_php5/jpgraph_bar.php');

function getEnergyPerHour($month, $year)
{
	return $year < 2010 ? 24 : $year > 2010 ? 20 : $month < 8 ? 24 : 20;
}

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

    	$sql = "SELECT energy FROM deltasole_wmz ORDER by energy ASC LIMIT 1";
		$result = mysql_query($sql);
		if ($myrow=mysql_fetch_array($result)) {
			$whole_energy = $myrow["energy"];
		}

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
			case 1: 
  				for ($i = 0; $i <= 23; $i++){	
					$begin = convertTimestamp($day_start, 
							$month_start, $year_start, $i, 0, 0);
 					$end   = convertTimestamp($day_stop, 
							$month_stop, $year_stop, $i, 59, 59);

     				$sql = "SELECT energy"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_max = $myrow["energy"];
   					}
    				$sql = "SELECT energy, DATE_FORMAT( `time`, '%H:%i') AS timeformat"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_min = $myrow["energy"];
						$date = $myrow["timeformat"];
   					} else {
						$date = 0;
					}
     				$sql = "SELECT boiler_hours1"
					   ." FROM vitocontrol WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_max = $myrow["boiler_hours1"];
   					}
    				$sql = "SELECT boiler_hours1"
					   ." FROM vitocontrol WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_min = $myrow["boiler_hours1"];
   					}
					if ($date && ($energy_max - $energy_min || 
								$boilerhours_max - $boilerhours_min)){
						$energy_solar[] = max($energy_max - $energy_min, 0.0001);
						$energy_oil[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($month_start, $year_start) * 1000, 0.0001);
						$dates[] = $date;
					}
   				}
				/*
				for ($i = 0; $i < count($dates); $i++){
				echo $energy_solar[$i] . " " . $energy_oil[$i] . " " . $dates[$i] . "<br>";
				}*/

				if ($energy_solar[0]){
   					$graph->xaxis->SetTickLabels($dates);
   					$p1 = new BarPlot($energy_solar);
   					$p1->SetColor('#0000FF'); 
   					$p1->SetLegend("Energie Solar(W/h)");
					$p1->SetValuePos('top');
					$p1->value->Show();
					$p1->SetFillColor('green');
					$p2 = new BarPlot($energy_oil);
   					$p2->SetColor('#FF0000'); 
   					$p2->SetLegend("Energie Oel(W/h)");
					$p2->SetValuePos('top');
					$p2->value->Show();
					$p2->SetFillColor('red');
					$group = new GroupBarPlot(array($p1,$p2));
					$graph->Add($group);
					$graph->Stroke();
				}
				break;
			case 2: 
  				for ($i = $day_start; $i <= $day_stop; $i++){	
					$begin = convertTimestamp($i, $month_start, $year_start, 0, 0, 0);
 					$end   = convertTimestamp($i, $month_stop, $year_stop, 23, 59, 59);
   					$sql = "SELECT energy"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_max = $myrow["energy"];
   					}
    				$sql = "SELECT energy, DATE_FORMAT( `date`, '%d.%m') AS timeformat"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_min = $myrow["energy"];
						$date = $myrow["timeformat"];
   					} else {
						$date = 0;
					}
     				$sql = "SELECT boiler_hours1"
					   ." FROM vitocontrol WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_max = $myrow["boiler_hours1"];
   					}
    				$sql = "SELECT boiler_hours1 "
					   ." FROM vitocontrol WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_min = $myrow["boiler_hours1"];
   					}
					if ($date && ($energy_max - $energy_min || 
								$boilerhours_max - $boilerhours_min)){
						$energy_solar[] = max(($energy_max - $energy_min) / 1000, 0.0001);
						$energy_oil[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($month_start, $year_start), 0.0001);
						$dates[] = $date;
					}
   				}
				/*
				for ($i = 0; $i < count($dates); $i++){
				echo $energy_solar[$i] . " " . $energy_oil[$i] . " " . $dates[$i] . "<br>";
				}*/

				if ($energy_solar[0]){
   					$graph->xaxis->SetTickLabels($dates);
   					$p1 = new BarPlot($energy_solar);
   					$p1->SetColor('#0000FF'); 
   					$p1->SetLegend("Energie Solar(kW/h)");
					$p1->SetValuePos('top');
					$p1->value->Show();
					$p1->SetFillColor('green');
					$p2 = new BarPlot($energy_oil);
   					$p2->SetColor('#FF0000'); 
   					$p2->SetLegend("Energie Oel(kW/h)");
					$p2->SetValuePos('top');
					$p2->value->Show();
					$p2->SetFillColor('red');
					$group = new GroupBarPlot(array($p1,$p2));
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
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_max = $myrow["energy"];
   					}
    				$sql = "SELECT energy, DATE_FORMAT( `date`, '%m.%Y') AS timeformat"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_min = $myrow["energy"];
						$date = $myrow["timeformat"];
   					} else {
						$date = 0;
					}
     				$sql = "SELECT boiler_hours1"
					   ." FROM vitocontrol WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_max = $myrow["boiler_hours1"];
   					}
    				$sql = "SELECT boiler_hours1 "
					   ." FROM vitocontrol WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_min = $myrow["boiler_hours1"];
   					}
					$sql = "SELECT *"
					   ." FROM wood WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC";
					$result = mysql_query($sql);
					$wood_top = 0;
					$wood_bottom = 0;
					if ($myrow=mysql_fetch_array($result)) {
						do { 
							$weight = $myrow["weight"];
							switch ($myrow["type"]){
								case "Hartholz":
								case "Weichholz":
								case "Mischholz":
									$factor = 4;
									break;
								case "Briketts":
								case "Pellets":
									$factor = 5;
									break;
								case "Kohle":
									$factor = 5; 
									/*TODO check if this is right*/
									break;
								default:
									$factor = 0;
									break;
							}
							if ($myrow["who"] == "Oben"){
								$wood_top += $weight * $factor * 0.8;
							} else {
								$wood_bottom += $weight * $factor * 0.8;
							}
						}while ($myrow=mysql_fetch_array($result));
   					}
					if ($date && ($energy_max - $energy_min || 
								$boilerhours_max - $boilerhours_min)){
						$energy_solar[] = max(($energy_max - $energy_min) / 1000, 0.0001);
						$energy_oil[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($i, $year_start), 0.0001);
						$energy_wood_top[] = $wood_top;
						$energy_wood_bottom[] = $wood_bottom;
						$energy_total[] = 
								(($energy_max - $energy_min) / 1000) +
								(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($i, $year_start)) + 
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
			case 4:  
    			for ($i = 1; $i <= 12; $i++){	
					$heating_season_begin = 7;
					$year = $year_start;
					$j = $i + $heating_season_begin;
					if ($j > 12){
						$j = $j - 12;
						if ($year == $year_start){
							$year++;
						}
					}
					$begin = convertTimestamp(1, $j, $year, 0, 0, 0);
					$end   = convertTimestamp(getLastDayOfMonth($j,$year), 
							$j, $year, 23, 59, 59);
					$sql = "SELECT energy"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_max = $myrow["energy"];
   					}
    				$sql = "SELECT energy, DATE_FORMAT( `date`, '%m.%Y') AS timeformat"
					   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$energy_min = $myrow["energy"];
						$date = $myrow["timeformat"];
   					} else {
						$date = 0;
					}
     				$sql = "SELECT boiler_hours1"
					   ." FROM vitocontrol WHERE timestamp >= $begin AND "
					   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_max = $myrow["boiler_hours1"];
   					}
    				$sql = "SELECT boiler_hours1 "
					   ." FROM vitocontrol WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC LIMIT 1";
					$result = mysql_query($sql);
					if ($myrow=mysql_fetch_array($result)) {
						$boilerhours_min = $myrow["boiler_hours1"];
   					}
					$sql = "SELECT *"
					   ." FROM wood WHERE timestamp >= $begin AND timestamp"
					   ." <= $end ORDER by timestamp ASC";
					$result = mysql_query($sql);
					$wood_top = 0;
					$wood_bottom = 0;
					if ($myrow=mysql_fetch_array($result)) {
						do { 
							$weight = $myrow["weight"];
							switch ($myrow["type"]){
								case "Hartholz":
								case "Weichholz":
								case "Mischholz":
									$factor = 4;
									break;
								case "Briketts":
								case "Pellets":
									$factor = 5;
									break;
								case "Kohle":
									$factor = 5; 
									/*TODO check if this is right*/
									break;
								default:
									$factor = 0;
									break;
							}
							if ($myrow["who"] == "Oben"){
								$wood_top += $weight * $factor * 0.8;
							} else {
								$wood_bottom += $weight * $factor * 0.8;
							}
						}while ($myrow=mysql_fetch_array($result));
   					}
					if ($date && ($energy_max - $energy_min || 
								$boilerhours_max - $boilerhours_min)){
						$energy_solar[] = max(($energy_max - $energy_min) / 1000, 0.0001);
						$energy_oil[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($i, $year_start), 0.0001);
						$energy_wood_top[] = $wood_top;
						$energy_wood_bottom[] = $wood_bottom;
						$energy_total[] = 
								(($energy_max - $energy_min) / 1000) +
								(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($i, $year_start)) + 
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
