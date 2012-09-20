<?php

require_once ('jpgraph_php5/jpgraph.php');
require_once ('jpgraph_php5/jpgraph_pie.php');

function getEnergyPerHour($month, $year)
{
	    return $year < 2010 ? 24 : $year > 2010 ? 20 : $month < 8 ? 24 : 20;
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

   		$graph = new PieGraph(500,300, "auto");
   		$graph->SetScale("textlin");
		$graph->SetMargin(50,100,20,30);
		switch ($interval){
			case 1: 
				$begin = convertTimestamp($day_start, 
						$month_start, $year_start, 0, 0, 0);
				$end   = convertTimestamp($day_stop, 
						$month_stop, $year_stop, 23, 59, 59);

 				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
				   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_max = $myrow["energy"];
				}
				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
				   ." <= $end ORDER by timestamp ASC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_min = $myrow["energy"];
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
				if ($energy_max - $energy_min || 
							$boilerhours_max - $boilerhours_min){
					$energy[] = max($energy_max - $energy_min, 0.0001);
					$energy[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($month_start, $year_start) * 1000, 0.0001);
					if ($energy_max - $energy_min){
						$colors = array('red','green');
						$legends = array('Solar','Oel');
					} else {
						$colors = array('green','red');
						$legends = array('Oel','Solar');
					}
				}
			    /*	
				for ($i = 0; $i < count($energy); $i++){
				echo $energy[$i] . "<br>";
				}
				*/
				if ($energy[0]){
   					$p1 = new PiePlot($energy);
					$p1->SetSliceColors($colors);
					$p1->SetLegends($legends);
					$p1->SetLabelType(PIE_VALUE_PER);
					$graph->Add($p1);
					$graph->Stroke();
				}
				break;
			case 2: 
				$begin = convertTimestamp(1, $month_start, $year_start, 0, 0, 0);
				$end   = convertTimestamp(31, $month_stop, $year_stop, 23, 59, 59);
				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
				   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_max = $myrow["energy"];
				}
				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
				   ." <= $end ORDER by timestamp ASC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_min = $myrow["energy"];
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
				if ($energy_max - $energy_min || 
							$boilerhours_max - $boilerhours_min){
					$energy[] = max($energy_max - $energy_min, 0.0001);
					$energy[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour($month_start, $year_start) * 1000, 0.0001);
					if ($energy_max - $energy_min){
						$colors = array('red','green');
						$legends = array('Solar','Oel');
					} else {
						$colors = array('green','red');
						$legends = array('Oel','Solar');
					}
				}
				/*
				for ($i = 0; $i < count($dates); $i++){
				echo $energy_solar[$i] . " " . $energy_oil[$i] . " " . $dates[$i] . "<br>";
				}*/

				if ($energy[0]){
   					$p1 = new PiePlot($energy);
					$p1->SetSliceColors($colors);
					$p1->SetLegends($legends);
					$p1->SetLabelType(PIE_VALUE_PER);
   					$p1->SetColor('#0000FF'); 
					$graph->Add($p1);
					$graph->Stroke();
				}
				break;
			case 3:  
				$begin = convertTimestamp(1, 1, $year_start, 0, 0, 0);
				$end   = convertTimestamp(31, 12, $year_stop, 23, 59, 59);
				
				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
				   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_max = $myrow["energy"];
				}
				$sql = "SELECT energy" 
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
				   ." <= $end ORDER by timestamp ASC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_min = $myrow["energy"];
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
				if ($energy_max - $energy_min || 
							$boilerhours_max - $boilerhours_min){
					$energy[] = max(($energy_max - $energy_min) / 1000, 0.0001);
					$energy[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour(1, $year_start), 0.0001);
					$energy[] = max($wood_top, 0.0001);
					$energy[] = max($wood_bottom, 0.0001);
					$colors = array('brown','black','red','green');
					$legends = array('Solar','Oel','Holz Oben','Holz Unten');
				}
			    /*	
				for ($i = 0; $i < count($dates); $i++){
				echo "Energie: " . $energy . "<br>";
				}
				*/
				if ($energy[0]){
   					$p1 = new PiePlot($energy);
					$p1->SetSliceColors($colors);
					$p1->SetLegends($legends);
					$p1->SetLabelType(PIE_VALUE_PER);
					$graph->Add($p1);
					$graph->Stroke();
				}
				break;
			case 4: 
				$begin = convertTimestamp(1, 8, $year_start, 0, 0, 0);
				$end   = convertTimestamp(31, 7, $year_start+1, 23, 59, 59);
				$sql = "SELECT energy"
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND "
				   ." timestamp <= $end ORDER by timestamp DESC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_max = $myrow["energy"];
				}
				$sql = "SELECT energy" 
				   ." FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp"
				   ." <= $end ORDER by timestamp ASC LIMIT 1";
				$result = mysql_query($sql);
				if ($myrow=mysql_fetch_array($result)) {
					$energy_min = $myrow["energy"];
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
				if ($energy_max - $energy_min || 
							$boilerhours_max - $boilerhours_min){
					$energy[] = max(($energy_max - $energy_min) / 1000, 0.0001);
					$energy[] = max(($boilerhours_max - $boilerhours_min) * getEnergyPerHour(8, $year_start), 0.0001);
					$energy[] = max($wood_top, 0.0001);
					$energy[] = max($wood_bottom, 0.0001);
					$colors = array('brown','black','red','green');
					$legends = array('Solar','Oel','Holz Oben','Holz Unten');
				}
			    /*	
				for ($i = 0; $i < count($dates); $i++){
				echo "Energie: " . $energy . "<br>";
				}
				*/
				if ($energy[0]){
   					$p1 = new PiePlot($energy);
					$p1->SetSliceColors($colors);
					$p1->SetLegends($legends);
					$p1->SetLabelType(PIE_VALUE_PER);
					$graph->Add($p1);
					$graph->Stroke();
				}
				break;
			default:
				break;
		}
	}
}
?>
