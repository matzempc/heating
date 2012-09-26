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
			case 7:
				$sql = "SELECT *"
					." FROM temperatures_rooms WHERE timestamp >= $begin AND timestamp <= "
					." $end ORDER by timestamp ASC";
				break;
			case 8:
				$sql = "SELECT *"
					." FROM heizkoerper WHERE timestamp >= $begin AND timestamp <= "
					." $end ORDER by timestamp ASC";
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

   		$graph = new Graph(1000,500, "auto");
   		$graph->SetScale("textlin");
		$graph->legend->SetAbsPos(5,5,'right','top');
		$graph->SetMargin(40,220,20,150);
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
	    					$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
					}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($ww_bottom);
   				$p1->SetColor('#0000FF'); 
   				$p1->SetLegend("Warmw. unten");
   				$p2 = new LinePlot($ww_top);
   				$p2->SetLegend("Warmw. oben");
   				$p2->SetColor('#00FF00');
   				$graph->Add($p1);
   				$graph->Add($p2);
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
   				$p2->SetColor('red');
   				$p3 = new LinePlot($buffer_mid);
   				$p3->SetColor('green');
   				$p3->SetLegend("Puffer Mitte");
				$p4 = new LinePlot($rl_temp);
   				$p4->SetColor('orange');
   				$p4->SetLegend("Ruecklauftemp");
   				$graph->Add($p1);
   				$graph->Add($p2);
   				$graph->Add($p3);
				$graph->Add($p4);
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
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}		
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($collector);
   				$p1->SetColor('#0000FF'); 
   				$p1->SetLegend("Koll. Citrin");
   				$p2 = new LinePlot($collector2);
   				$p2->SetLegend("Koll. Wagner");
   				$p2->SetColor('#00FF00');
   				$p3 = new LinePlot($vl);
   				$p3->SetLegend("Temperatur VL");
   				$p3->SetColor('#FF0000');
				$p4 = new LinePlot($rl);
   				$p4->SetLegend("Temperatur RL");
   				$p4->SetColor('#FF00FF');
				$p5 = new LinePlot($solarization);
   				$p5->SetLegend("Einstrahlung");
   				$p5->SetColor('#FFFF00');
   				$graph->SetYScale(0,"lin");
				$graph->Add($p1);
   				$graph->Add($p2);
   				$graph->Add($p3);
				$graph->Add($p4);
				$graph->AddY(0,$p5);
   				$graph->Stroke();
				break;
			case 4:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
							$boileris[] = $myrow["temp_boiler_is"];
	 						$boilertarget[] = $myrow["temp_boiler_target"];
							$tempout_mid[] = $myrow["temp_out_red"];
							if ($myrow["temp_out"] < 65) {
								$tempout[] = $myrow["temp_out"];
								$outtemp = $myrow["temp_out"];
							} else {
								$tempout[] = $outtemp;
							}
							if ($myrow["temp_exhaust"] <90){
								$tempexhaust[] = $myrow["temp_exhaust"];
								$exhausttemp = $myrow["temp_exhaust"];
							} else {
								$tempexhaust[] = $exhausttemp;
							}
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
				$p3 = new LinePlot($tempout);
   				$p3->SetLegend("Aussentemperatur");
   				$p3->SetColor('red');
				$p4 = new LinePlot($tempout_mid);
   				$p4->SetLegend("Aussentemperatur gedaempft");
   				$p4->SetColor('violet');
				$p5 = new LinePlot($tempexhaust);
   				$p5->SetLegend("Abgastemperatur");
   				$p5->SetColor('black');
				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->Add($p3);
				$graph->Add($p4);
   				$graph->Stroke();
				break;
			case 5:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
	 						$vl[] = $myrow["temp_vl_is_M2"];
							$rl[] = $myrow["temp_rl_is_M2"];
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
						$sqldeltasol = "SELECT temp_rl "
						    ." FROM deltasole WHERE timestamp >= $begindeltasol AND "
						    ."timestamp <= $enddeltasol "
						    ."ORDER by timestamp ASC";
						$resultdeltasol = mysql_query($sqldeltasol);
						if ($myrow2=mysql_fetch_array($resultdeltasol)) {
							$rl_M1[] = $myrow2["temp_rl"];
						} else {
							$rl_M1[] = 0.0001;
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($vl);
   				$p1->SetLegend("Temperatur VL");
   				$p1->SetColor('#FF0000');
				$p2 = new LinePlot($rl);
   				$p2->SetLegend("Temperatur RL");
   				$p2->SetColor('#0000FF');
				$p3 = new LinePlot($rl_M1);
   				$p3->SetLegend("Temperatur RL M1");
   				$p3->SetColor('#00FF00');
				$graph->Add($p1);
   				$graph->Add($p2);
				$graph->Add($p3);
   				$graph->Stroke();
				break;
			case 6:
  				if ($myrow=mysql_fetch_array($result)) {
      				do {
						if (($i % $factor) == 0){
	 						$vl[] = $myrow["temp_vl_is_M2"];
							$rl[] = $myrow["temp_rl_is_M2"];
							$dates[] = $myrow["dateformat"]
            						. " " . $myrow["time"];
						}
						$i++;
      				}while ($myrow=mysql_fetch_array($result));
   				}
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($vl);
   				$p1->SetLegend("Temperatur VL");
   				$p1->SetColor('#FF0000');
				$p2 = new LinePlot($rl);
   				$p2->SetLegend("Temperatur RL");
   				$p2->SetColor('#0000FF');
				$graph->Add($p1);
   				$graph->Add($p2);
   				$graph->Stroke();
				break;
			case 7:
				$buero_offset = 0 /*0.7*/;
			    $min_temp = 10;
				$max_temp = 40;	
  				if ($myrow=mysql_fetch_array($result)) {
					do {
						if (($i % $factor) == 0){
         					$buero[] = $myrow["buero"] + $buero_offset;
							$wz_oben[] = $myrow["wohnzimmer_oben"] > $min_temp 
								&& $myrow["wohnzimmer_oben"] < $max_temp ?
								$myrow["wohnzimmer_oben"] : "-"; 
							$wz_unten[] = $myrow["wohnzimmer_unten"] > $min_temp 
								&& $myrow["wohnzimmer_unten"] < $max_temp ?
								$myrow["wohnzimmer_unten"] : "-"; 
							$badezimmer_unten[] = $myrow["badezimmer_unten"] > $min_temp 
								&& $myrow["badezimmer_unten"] < $max_temp ?
								$myrow["badezimmer_unten"] : "-"; 
							$max_zimmer[] = $myrow["max_zimmer"] > $min_temp 
								&& $myrow["max_zimmer"] < $max_temp ?
								$myrow["max_zimmer"] : "-";
							$i2[] = $myrow["schlafzimmer_unten"] >  $min_temp
								&& $myrow["schlafzimmer_unten"] < $max_temp ?
								$myrow["schlafzimmer_unten"] : "-";
							$i6[] = $myrow["maja_zimmer"] >  $min_temp
								&& $myrow["maja_zimmer"] < $max_temp ?
								$myrow["maja_zimmer"] : "-"; 
							$i7[] = $myrow["eingang_unten"] >  $min_temp
								&& $myrow["eingang_unten"] < $max_temp ?
								$myrow["eingang_unten"] : "-"; 
							$i8[] = $myrow["badezimmer_oben"] >  $min_temp
								&& $myrow["badezimmer_oben"] < $max_temp ?
								$myrow["badezimmer_oben"] : "-";
							$arbeitszimmer[] = $myrow["arbeitszimmer"] > $min_temp
								&& $myrow["arbeitszimmer"] < $max_temp ?
								$myrow["arbeitszimmer"] : "-";
							$dates[] = $myrow["timestamp"];
						}
						$i++;
					}while ($myrow=mysql_fetch_array($result));
   				}
				$texttickint = 3;
   				$graph->xaxis->SetTextTickInterval($texttickint);
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($buero);
   				$p1->SetColor('green'); 
   				$p1->SetLegend("Buero");
				$p2 = new LinePlot($wz_oben);
   				$p2->SetColor('red'); 
   				$p2->SetLegend("Wohnzimmer oben");
				$p3 = new LinePlot($arbeitszimmer);
   				$p3->SetColor('blue'); 
   				$p3->SetLegend("Arbeitszimmer");
				$p4 = new LinePlot($max_zimmer);
   				$p4->SetColor('yellow'); 
   				$p4->SetLegend("Max Zimmer");
				$p5 = new LinePlot($badezimmer_unten);
   				$p5->SetColor('black'); 
   				$p5->SetLegend("Badezimmer unten");
				$p6 = new LinePlot($i2);
   				$p6->SetColor('violet'); 
   				$p6->SetLegend("Schlafzimmer unten");
				$p7 = new LinePlot($i6);
   				$p7->SetColor('orange'); 
   				$p7->SetLegend("Maja Zimmer");
				$p8 = new LinePlot($i7);
   				$p8->SetColor('brown'); 
   				$p8->SetLegend("Eingang unten");
				$p9 = new LinePlot($i8);
   				$p9->SetColor('darkmagenta'); 
   				$p9->SetLegend("Badezimmer oben");
				$p10 = new LinePlot($wz_unten);
   				$p10->SetColor('slategray3'); 
   				$p10->SetLegend("Wohnzimmer unten");
	
   				$graph->Add($p1);
				$graph->Add($p2);
				$graph->Add($p3);
				$graph->Add($p4);
				$graph->Add($p5);
				$graph->Add($p6);
				$graph->Add($p7);
				$graph->Add($p8);
				$graph->Add($p9);
				$graph->Add($p10);
   				$graph->Stroke();
			case 8:
			    $min_temp = 5;
				$max_temp = 90;	
  				if ($myrow=mysql_fetch_array($result)) {
					do {
						if (($i % $factor) == 0){
							$az_vl[] = $myrow["arbeitszimmer_vl"] > $min_temp 
								&& $myrow["arbeitszimmer_vl"] < $max_temp ?
								$myrow["arbeitszimmer_vl"] : "-"; 
							$az_rl[] = $myrow["arbeitszimmer_rl"] > $min_temp 
								&& $myrow["arbeitszimmer_rl"] < $max_temp ?
								$myrow["arbeitszimmer_rl"] : "-"; 
							$dates[] = $myrow["timestamp"];
						}
						$i++;
					}while ($myrow=mysql_fetch_array($result));
   				}
				//$texttickint = 20;
   				$graph->xaxis->SetTextTickInterval($texttickint);
   				$graph->xaxis->SetTickLabels($dates);
   				$p1 = new LinePlot($az_vl);
   				$p1->SetColor('green'); 
   				$p1->SetLegend("Arbeitszimmer_VL");
				$p2 = new LinePlot($az_rl);
   				$p2->SetColor('red'); 
   				$p2->SetLegend("Arbeitszimmer_RL");
	
   				$graph->Add($p1);
				$graph->Add($p2);
   				$graph->Stroke();
				break;
				break;
			default:
				break;
		}
	}
}
?>
