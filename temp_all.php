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
		case 7:
			$sql = "SELECT *"
				." FROM temperatures_rooms WHERE timestamp >= $begin AND timestamp <= "
				." $end ORDER by timestamp ASC";
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
 		$graph->SetScale("textlin");
		$graph->legend->SetLayout(LEGEND_VERT);
	    $graph->legend->SetAbsPos(1000,5,'left','bottom');
	$graph->SetMargin(30,220,20,150);
 		$texttickint = 20;
 		$graph->xaxis->SetTextTickInterval($texttickint);
	$graph->xaxis->SetLabelAngle(90);
	$graph->xaxis->SetPos('min');
	switch ($_GET["type"]){
		case 1: 
		case 2:
		case 3:
				if ($myrow=$result->fetch_array()) {
				do {
					if (($i % $factor) == 0){
						$collector[] = $myrow["temp_collector"];
 						$vl[] = $myrow["temp_wmz_vl"];
						$rl[] = $myrow["temp_wmz_rl"];
						$solarization[] = $myrow["solarization"];
       					$ww_bottom[] = $myrow["temp_ww_bottom"];
						$buffer_bottom[] = $myrow["temp_buffer_bottom"];
 						$buffer_top[] = $myrow["temp_buffer_top"];
    					$dates[] = $myrow["dateformat"]
          						. " " . $myrow["time"];
					}
					$i++;
				}while ($myrow=$result->fetch_array());
 				}
 				$graph->xaxis->SetTickLabels($dates);
 				$p1 = new LinePlot($ww_bottom);
 				$p1->SetColor('#00DDFF'); 
 				$p1->SetLegend("Warmw. unten");
			$p2 = new LinePlot($buffer_bottom);
 				$p2->SetColor('#A0D010'); 
 				$p2->SetLegend("Puffer unten");
 				$p3 = new LinePlot($buffer_top);
 				$p3->SetLegend("Puffer oben");
 				$p3->SetColor('#000000');
			$p4 = new LinePlot($collector);
 				$p4->SetColor('#0000FF'); 
 				$p4->SetLegend("Koll. Citrin");
 				$p5 = new LinePlot($vl);
 				$p5->SetLegend("Temperatur VL");
 				$p5->SetColor('#FF0000');
			$p6 = new LinePlot($rl);
 				$p6->SetLegend("Temperatur RL");
 				$p6->SetColor('#FF00FF');
			$p7 = new LinePlot($solarization);
 				$p7->SetLegend("Einstrahlung");
 				$p7->SetColor('#FFFF00');
 				$graph->Add($p1);
 				$graph->Add($p2);
 				$graph->Add($p3);
 				$graph->Add($p4);
			$graph->Add($p5);
 				$graph->Add($p6);
			$graph->SetYScale(0,"lin");
			$graph->AddY(0,$p7);
 				$graph->Stroke();
			break;
		case 4:
		case 5:
				if ($myrow=$result->fetch_array()) {
    				do {
					if (($i % $factor) == 0){
						if ($myrow["temp_vl_is_M2"] < 85){
							$vl[] = $myrow["temp_vl_is_M2"];
						} else {
							$vl[] = "-";
						}
						if ($myrow["temp_rl_is_M2"] < 85){
							$rl[] = $myrow["temp_rl_is_M2"];
						} else {
							$rl[] = "-";
						}
						if ($myrow["temp_boiler_is"] < 90){
							$boileris[] = $myrow["temp_boiler_is"];
						} else {
							$boileris[] = "-";
						}
						if ($myrow["temp_boiler_target"] < 90){
							$boilertarget[] = $myrow["temp_boiler_target"];
						} else {
							$boilertarget[] = "-";
						}
						if ($myrow["temp_exhaust"] < 90){
							$tempexhaust[] = $myrow["temp_exhaust"];
							$exhasuttemp = $myrow["temp_exhaust"];
						} else {
							$tempexhaust[] = $exhausttemp;
						}
						$dates[] = $myrow["dateformat"]
          						. " " . $myrow["time"];
						$time = (string) $myrow["time"];
						$hour = (int) strtok($time, ":");
						$minute = (int) strtok(":");
						$begindeltasol = convertTimestamp($day_start, $month_start, $year_start,
							   	$hour, $minute, 0);
     						$enddeltasol   = convertTimestamp($day_stop, $month_stop, $year_stop, 
								$hour, $minute, 59);
						$sqldeltasol = "SELECT temp_rl, temp_buffer_top "
			       			." FROM deltasole WHERE timestamp >= $begindeltasol AND "
							."timestamp <= $enddeltasol "
			       			."ORDER by timestamp ASC LIMIT 1";
						/*echo "$sqldeltasol <br>";*/
						$resultdeltasol = $connection->query($sqldeltasol);
						if ($myrow2=$resultdeltasol->fetch_array()) {
			            	$rl_M1[] = $myrow2["temp_rl"];
							$buffer_top[] = $myrow2["temp_buffer_top"];
						} else {
							$rl_M1[] = 0.0001;
							$buffer_top[] = 0.0001;
						}
					}
					$i++;
    				}while ($myrow=$result->fetch_array());
 				}	         
 				$graph->xaxis->SetTickLabels($dates);
 				$p1 = new LinePlot($boileris);
 				$p1->SetColor('blue'); 
 				$p1->SetLegend("Kessel IST");
 				$p2 = new LinePlot($boilertarget);
 				$p2->SetLegend("Kessel SOLL");
 				$p2->SetColor('green');
			$p3 = new LinePlot($vl);
 				$p3->SetLegend("Temperatur VL");
 				$p3->SetColor('red');
			$p4 = new LinePlot($rl);
 				$p4->SetLegend("Temperatur RL");
 				$p4->SetColor('black');
			$p5 = new LinePlot($rl_M1);
			$p5->SetLegend("Temperatur RL M1");
			$p5->SetColor('orange');
			$p6 = new LinePlot($buffer_top);
			$p6->SetLegend("Puffer oben");
			$p6->SetColor('violet');
			$p7 = new LinePlot($tempexhaust);
			$p7->SetLegend("Abgastemperatur");
			$p7->SetColor('brown');
			$graph->Add($p1);
 				$graph->Add($p2);
			$graph->Add($p3);
 				$graph->Add($p4);
			$graph->Add($p5);
			$graph->Add($p6);
			$graph->Add($p7);
 				$graph->Stroke();
			break;
		case 6:
				if ($myrow=$result->fetch_array()) {
    				do {
					if (($i % $factor) == 0){
						if ($myrow["temp_vl_is_M2"] < 85){
							$vl[] = $myrow["temp_vl_is_M2"];
						} else {
							$vl[] = "-";
						}
						if ($myrow["temp_rl_is_M2"] < 85){
							$rl[] = $myrow["temp_rl_is_M2"];
						} else {
							$rl[] = "-";
						}
						if ($myrow["temp_boiler_is"] < 90){
							$boileris[] = $myrow["temp_boiler_is"];
						} else {
							$boileris[] = "-";
						}
						if ($myrow["temp_boiler_target"] < 90){
							$boilertarget[] = $myrow["temp_boiler_target"];
						} else {
							$boilertarget[] = "-";
						}
						if ($myrow["temp_exhaust"] < 90){
							$tempexhaust[] = $myrow["temp_exhaust"];
							$exhasuttemp = $myrow["temp_exhaust"];
						} else {
							$tempexhaust[] = $exhausttemp;
						}
						$dates[] = $myrow["dateformat"]
          						. " " . $myrow["time"];
					}
					$i++;
    				}while ($myrow=$result->fetch_array());
 				}	         
 				$graph->xaxis->SetTickLabels($dates);
 				$p1 = new LinePlot($boileris);
 				$p1->SetColor('blue'); 
 				$p1->SetLegend("Kessel IST");
 				$p2 = new LinePlot($boilertarget);
 				$p2->SetLegend("Kessel SOLL");
 				$p2->SetColor('green');
			$p3 = new LinePlot($vl);
 				$p3->SetLegend("Temperatur VL");
 				$p3->SetColor('red');
			$p4 = new LinePlot($rl);
 				$p4->SetLegend("Temperatur RL");
 				$p4->SetColor('black');
			$p5 = new LinePlot($tempexhaust);
			$p5->SetLegend("Abgastemperatur");
			$p5->SetColor('brown');
			$graph->Add($p1);
 				$graph->Add($p2);
			$graph->Add($p3);
 				$graph->Add($p4);
			$graph->Add($p5);
 				$graph->Stroke();
			break;
		case 7: 
				if ($myrow=$result->fetch_array()) {
				do {
					if (($i % $factor) == 0){
						$wz_oben[] = $myrow["wohnzimmer_oben_feuchte"] != 0 ?
							$myrow["wohnzimmer_oben_feuchte"] : "-"; 
						$wz_unten[] = $myrow["wohnzimmer_unten_feuchte"] != 0 ?
							$myrow["wohnzimmer_unten_feuchte"] : "-"; 
						$badezimmer_unten[] = $myrow["badezimmer_unten_feuchte"] != 0 ?
							$myrow["badezimmer_unten_feuchte"] : "-"; 
						$max_zimmer[] = $myrow["max_zimmer_feuchte"] != 0 ?
							$myrow["max_zimmer_feuchte"] : "-";
						$i2[] = $myrow["schlafzimmer_unten_feuchte"] != 0 ?
							$myrow["schlafzimmer_unten_feuchte"] : "-";
						$i6[] = $myrow["maja_zimmer_feuchte"] != 0 ?
							$myrow["maja_zimmer_feuchte"] : "-"; 
						$i7[] = $myrow["arbeitszimmer_feuchte"] != 0 ?
							$myrow["arbeitszimmer_feuchte"] : "-"; 
						$i8[] = $myrow["badezimmer_oben_feuchte"] != 0 ?
							$myrow["badezimmer_oben_feuchte"] : "-";
						$kueche_ug[] = $myrow["kueche_ug_feuchte"] != 0 ?
							$myrow["kueche_ug_feuchte"] : "-";
						$abstellraum_eg[] = $myrow["abstellraum_eg_feuchte"] != 0 ?
							$myrow["abstellraum_eg_feuchte"] : "-";
						$dates[] = $myrow["timestamp"];
					}
					$i++;
				}while ($myrow=$result->fetch_array());
 				}
			$texttickint = 3;
 				$graph->xaxis->SetTextTickInterval($texttickint);
 				$graph->xaxis->SetTickLabels($dates);
			$p1 = new LinePlot($wz_oben);
 				$p1->SetColor('red'); 
 				$p1->SetLegend("Wohnzimmer oben");
			$p2 = new LinePlot($kueche_ug);
 				$p2->SetColor('green'); 
 				$p2->SetLegend("Kueche UG");
			$p3 = new LinePlot($max_zimmer);
 				$p3->SetColor('yellow'); 
 				$p3->SetLegend("Max Zimmer");
			$p4 = new LinePlot($badezimmer_unten);
 				$p4->SetColor('black'); 
 				$p4->SetLegend("Badezimmer unten");
			$p5 = new LinePlot($i2);
 				$p5->SetColor('violet'); 
 				$p5->SetLegend("Schlafzimmer unten");
			$p6 = new LinePlot($i6);
 				$p6->SetColor('orange'); 
 				$p6->SetLegend("Maja Zimmer");
			$p7 = new LinePlot($i7);
 				$p7->SetColor('brown'); 
 				$p7->SetLegend("Arbeitszimmer");
			$p8 = new LinePlot($i8);
 				$p8->SetColor('darkmagenta'); 
 				$p8->SetLegend("Badezimmer oben");
			$p9 = new LinePlot($wz_unten);
 				$p9->SetColor('slategray3'); 
 				$p9->SetLegend("Wohnzimmer unten");
			$p10 = new LinePlot($abstellraum_eg);
 				$p9->SetColor('cyan'); 
 				$p9->SetLegend("Abstellraum EG");
 				$graph->Add($p1);
			$graph->Add($p2);
			$graph->Add($p3);
			$graph->Add($p4);
			$graph->Add($p5);
			$graph->Add($p6);
			$graph->Add($p7);
			$graph->Add($p8);
			$graph->Add($p9);
 				$graph->Stroke();
			break;
		default:
			break;
	}
}
?>
