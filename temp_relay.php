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

if ($connection = mysql_connect('localhost','heating','heating')){
	if(mysql_select_db('heating',$connection)){
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
			case 2:
			case 3:	
    			$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat"
		   			." FROM deltasole WHERE timestamp >= $begin AND timestamp <= $end "
					."ORDER by timestamp ASC";
				break;
			case 4:
			case 5:
			case 6:
				$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat"
		   			." FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end "
					."ORDER by timestamp ASC";
				break;
			default:
				break;
		}
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		$texttickint = (integer) ($num % 40);
        if ($num > 500){
			$factor=(integer) ($num/500); 
		} else {
			$factor = 1;
		}
		$maxy = 1.05;
		switch ($_GET["type"]){
			case 1:
			case 2:
			case 3:
				$maxy *= 100;
			default:
				break;
		}
   		$graph = new Graph(1000,500, "auto");
   		$graph->SetScale("textlin");
		$graph->legend->SetAbsPos(5,5,'right','top');
		$graph->SetMargin(30,220,20,150);
   		$texttickint = 20;
   		$graph->xaxis->SetTextTickInterval($texttickint);
		$graph->xaxis->SetLabelAngle(90);
		$graph->xaxis->SetPos('min');
		switch ($_GET["type"]){
			case 1: 
  				if ($myrow=mysql_fetch_array($result)) {
					do {
						if (($i % $factor) == 0){
         					$ww_bottom[] = $myrow["temp_ww_bottom"];
	 						$ww_top[] = $myrow["temp_ww_top"];
							$solarpump[] = $myrow["solarpump"];
							$ww_relay[] = $myrow["ww_relay"];
	    					$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
					}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($ww_bottom);
   				$p1->SetColor('blue'); 
   				$p1->SetLegend("Warmw. unten");
   				$p2 = new LinePlot($ww_top);
   				$p2->SetLegend("Warmw. oben");
   				$p2->SetColor('green');
				$p3 = new LinePlot($solarpump);
				$p3->SetLegend("Solar Pumpe");
				$p3->SetColor('red');
				$p4 = new LinePlot($ww_relay);
				$p4->SetColor('black');
				$p4->SetLegend("WW Relais");
   				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->SetYScale(0,"lin",0,$maxy);
				$graph->AddY(0, $p3);
				$graph->AddY(0, $p4);
   				$graph->Stroke();
				break;
			case 2:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
         					$buffer_bottom[] = $myrow["temp_buffer_bottom"];
	 						$buffer_mid[] = $myrow["temp_buffer_mid"];
	 						$buffer_top[] = $myrow["temp_buffer_top"];
							$rl_temp[] = $myrow["temp_rl"];
							$solarpump[] = $myrow["solarpump"];
							$buffer_relay[] = $myrow["buffer_relay"];
							$rl_relay[] = $myrow["rl_relay"];
							$heatingpump[] = $myrow["heatingpump"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
					}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($buffer_bottom);
   				$p1->SetColor('blue'); 
   				$p1->SetLegend("Puffer unten");
   				$p2 = new LinePlot($buffer_top);
   				$p2->SetLegend("Puffer oben");
   				$p2->SetColor('green');
   				$p3 = new LinePlot($buffer_mid);
   				$p3->SetColor('red'); 
   				$p3->SetLegend("Puffer Mitte");
				$p4 = new LinePlot($rl_temp);
   				$p4->SetColor('yellow'); 
   				$p4->SetLegend("Ruecklauftemp");
				$p5 = new LinePlot($solarpump);
				$p5->SetLegend("Solar Pumpe");
				$p5->SetColor('black');
				$p6 = new LinePlot($buffer_relay);
				$p6->SetColor('orange');
				$p6->SetLegend("Puffer Relais");
				$p7 = new LinePlot($rl_relay);
				$p7->SetLegend("Ruecklauf Relais");
				$p7->SetColor('violet');
				$p8 = new LinePlot($heatingpump);
				$p8->SetColor('lightblue');
				$p8->SetLegend("Heizungspumpe");
   				$graph->Add($p1);
   				$graph->Add($p2);
   				$graph->Add($p3);
				$graph->Add($p4);
				$graph->SetYScale(0,"lin",0,$maxy);
				$graph->AddY(0, $p5);
				$graph->AddY(0, $p6);
				$graph->AddY(0, $p7);
				$graph->AddY(0, $p8);
   				$graph->Stroke();
				break;
			case 3:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
							$collector[] = $myrow["temp_collector"];
	 						$collector2[] = $myrow["temp_collector2"];
	 						$vl[] = $myrow["temp_wmz_vl"];
							$rl[] = $myrow["temp_wmz_rl"];
							$solarization[] = $myrow["solarization"];
							$solarpump[] = $myrow["solarpump"];
							$heatingpump[] = $myrow["heatingpump"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}		
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($collector);
   				$p1->SetColor('blue'); 
   				$p1->SetLegend("Koll. Citrin");
   				$p2 = new LinePlot($collector2);
   				$p2->SetLegend("Koll. Wagner");
   				$p2->SetColor('green');
   				$p3 = new LinePlot($vl);
   				$p3->SetLegend("Temperatur VL");
   				$p3->SetColor('red');
				$p4 = new LinePlot($rl);
   				$p4->SetLegend("Temperatur RL");
   				$p4->SetColor('black');
				$p5 = new LinePlot($solarization);
   				$p5->SetLegend("Einstrahlung");
   				$p5->SetColor('yellow');
				$p6 = new LinePlot($solarpump);
   				$p6->SetLegend("Solar Pumpe");
   				$p6->SetColor('orange');
				$p7 = new LinePlot($heatingpump);
   				$p7->SetLegend("Heizungspumpe");
   				$p7->SetColor('violet');

   				$graph->SetYScale(0,"lin");
				$graph->SetYScale(1,"lin",0,$maxy);
				$graph->Add($p1);
   				$graph->Add($p2);
   				$graph->Add($p3);
				$graph->Add($p4);
				$graph->AddY(0,$p5);
				$graph->AddY(1,$p6);
				$graph->AddY(1,$p7);
   				$graph->Stroke();
				break;
			case 4:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
							$boileris[] = $myrow["temp_boiler_is"];
	 						$boilertarget[] = $myrow["temp_boiler_target"];
							$boilerstate[] = $myrow["boiler_state"];
							$pump_m1[] = $myrow["pump_M1"];
							$pump_m2[] = $myrow["pump_M2"];
							$pump_ww[] = $myrow["pump_ww"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}		
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($boileris);
   				$p1->SetColor('blue'); 
   				$p1->SetLegend("Kessel IST");
   				$p2 = new LinePlot($boilertarget);
   				$p2->SetLegend("Kessel SOLL");
   				$p2->SetColor('green');
				$p3 = new LinePlot($boilerstate);
   				$p3->SetLegend("Brenner");
   				$p3->SetColor('red');
				$p4 = new LinePlot($pump_m1);
   				$p4->SetLegend("Heizungspumpe1");
   				$p4->SetColor('violet');
				$p5 = new LinePlot($pump_m2);
   				$p5->SetLegend("Heizungspumpe2");
   				$p5->SetColor('black');
				$p6 = new LinePlot($pump_ww);
   				$p6->SetLegend("WW Pumpe");
   				$p6->SetColor('yellow');
	
				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->SetYScale(0,"lin",0,$maxy);
				$graph->AddY(0,$p3);
				$graph->AddY(0,$p4);
				$graph->AddY(0,$p5);
 				$graph->AddY(0,$p6);

   				$graph->Stroke();
				break;
			case 5:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
	 						$vl[] = $myrow["temp_vl_is_M2"];
							$rl[] = $myrow["temp_rl_is_M2"];
							$pump_m1[] = $myrow["pump_M1"];
							$pump_m2[] = $myrow["pump_M2"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$time = (string) $myrow["time"];
						$hour = (int) strtok($time, ":");
						$minute = (int) strtok(":");
						$begindeltasol = convertTimestamp($day_start, $month_start, $year_start,
						$hour, $minute, 0);
						$enddeltasol   = convertTimestamp($day_stop, $month_stop, $year_stop,
						$hour, $minute, 59);
						$sqldeltasol = "SELECT temp_rl, temp_buffer_top "
						    ."AS dateformat "
						    ."FROM deltasole WHERE timestamp >= $begindeltasol AND "
						    ."timestamp <= $enddeltasol "
						    ."ORDER by timestamp ASC LIMIT 1";
						$resultdeltasol = mysql_query($sqldeltasol);
						if ($myrow2=mysql_fetch_array($resultdeltasol)) {
							$rl_M1[] = $myrow2["temp_rl"];
							$buffer_top[] = $myrow2["temp_buffer_top"];
						} else {
							$rl_M1[] = 0.0001;
							$buffer_top[] = 0.0001;
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($vl);
   				$p1->SetLegend("Temperatur VL");
   				$p1->SetColor('blue');
				$p2 = new LinePlot($rl);
   				$p2->SetLegend("Temperatur RL");
   				$p2->SetColor('green');
				$p3 = new LinePlot($rl_M1);
   				$p3->SetLegend("Temperatur RL M1");
   				$p3->SetColor('red');
				$p4 = new LinePlot($buffer_top);
   				$p4->SetLegend("Puffer oben");
   				$p4->SetColor('orange');
				$p5 = new LinePlot($pump_m1);
   				$p5->SetLegend("Heizungspumpe1");
   				$p5->SetColor('black');
				$p6 = new LinePlot($pump_m2);
   				$p6->SetLegend("Heizungspumpe2");
   				$p6->SetColor('violet');

				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->Add($p3);
				$graph->Add($p4);
				$graph->SetYScale(0,"lin",0,$maxy);
				$graph->AddY(0,$p5);
				$graph->AddY(0,$p6);

   				$graph->Stroke();
				break;
			case 6:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
	 						$vl[] = $myrow["temp_vl_is_M2"];
							$rl[] = $myrow["temp_rl_is_M2"];
							$pump_m1[] = $myrow["pump_M1"];
							$pump_m2[] = $myrow["pump_M2"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($vl);
   				$p1->SetLegend("Temperatur VL");
   				$p1->SetColor('blue');
				$p2 = new LinePlot($rl);
   				$p2->SetLegend("Temperatur RL");
   				$p2->SetColor('green');
				$p3 = new LinePlot($pump_m1);
   				$p3->SetLegend("Heizungspumpe1");
   				$p3->SetColor('black');
				$p4 = new LinePlot($pump_m2);
   				$p4->SetLegend("Heizungspumpe2");
   				$p4->SetColor('violet');

				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->SetYScale(0,"lin",0,$maxy);
				$graph->AddY(0,$p3);
				$graph->AddY(0,$p4);
   				$graph->Stroke();
				break;
			default:
				break;
		}
	}
}
?>
