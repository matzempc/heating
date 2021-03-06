<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
<META HTTP-EQUIV="Refresh" CONTENT="300">
<link rel="stylesheet" href="heating.css">
<title>Heizung</title>
  </head>
  <body>
<div align="center">
<?php

/*
$oil_2009 = 2990; ???
$oil_2010 = 2433;
$oil_2011 = 2500; circa da erst Anfang Dezember 2012 getankt worden ist
$oil_2012 = 557; Rest von 2011 da insg. 3057l getankt wurden und die HeizSaison schon begonnen hat
*/

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

function converthourswithdays($dec_time){
        if (substr_count($dec_time, ",") > 0)
        $dec_time = str_replace(",", ".", $dec_time);
    $dec_time = floatval($dec_time);
    #strip hours
        $in_hours = abs($dec_time);
    $in_seconds = $in_hours*3600;
    $in_seconds = $in_seconds + (3600*($dec_time-$in_hours));
    #convert readable
    $days = $in_seconds / 86400 % 7;
    $hours = $in_seconds / 3600 %24;
    $minutes = $in_seconds / 60 % 60;
    $seconds = $in_seconds % 60;
    if($hours < 10) $hoursstring = strtok("0" . $hours, ".");
    else $hoursstring = (string) strtok($hours, ".");
    if($minutes < 10) $minutesstring = "0" . $minutes;
    else $minutesstring = (string) $minutes;
    if($seconds < 10) $secondsstring = "0" . $seconds;
    else $secondsstring = (string) $seconds;
    $string = sprintf("%d Tage %s:%s:%s",$days, $hoursstring,$minutesstring,$secondsstring);
 
    return (string)$string;
}

function converthours($dec_time){
        if (substr_count($dec_time, ",") > 0)
        $dec_time = str_replace(",", ".", $dec_time);
    $dec_time = floatval($dec_time);
    #strip hours
        $in_hours = abs($dec_time);
    $in_seconds = $in_hours*3600;
    $in_seconds = $in_seconds + (3600*($dec_time-$in_hours));
    #convert readable
    $hours = $in_seconds / 3600;
    $minutes = $in_seconds / 60 % 60;
    $seconds = $in_seconds % 60;
    if($hours < 10) $hoursstring = strtok("0" . $hours, ".");
    else $hoursstring = (string) strtok($hours, ".");
    if($minutes < 10) $minutesstring = "0" . $minutes;
    else $minutesstring = (string) $minutes;
    if($seconds < 10) $secondsstring = "0" . $seconds;
    else $secondsstring = (string) $seconds;
    $string = sprintf("%s:%s:%s",$hoursstring,$minutesstring,$secondsstring);
    return (string)$string;
}

function convertmins($dec_time){
        if (substr_count($dec_time, ",") > 0)
        $dec_time = str_replace(",", ".", $dec_time);
    $dec_time = floatval($dec_time);
    #strip hours
        $in_hours = abs($dec_time);
    $in_seconds = $in_hours*3600;
    $in_seconds = $in_seconds + (3600*($dec_time-$in_hours));
    #convert readable
    $minutes = $in_seconds / 60 % 60;
    $seconds = $in_seconds % 60;
    if($minutes < 10) $minutesstring = "0" . $minutes;
    else $minutesstring = (string) $minutes;
    if($seconds < 10) $secondsstring = "0" . $seconds;
    else $secondsstring = (string) $seconds;
    $string = sprintf("%s:%s (mins)",$minutesstring,$secondsstring);
    return (string)$string;
}

if ($connection = mysqli_connect('localhost','heating','heating','heating')){
	
		$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat FROM deltasole ORDER BY  `deltasole`.`index` DESC LIMIT 1";
		$result = $connection->query($sql);
		$line = $result->fetch_array();

		$day = date("j");
		$month = date("n");
		$year =  date("Y");	

		$begin = convertTimestamp($day, $month, $year, 0, 0, 0);
        $end   = convertTimestamp($day, $month, $year, 23, 59, 59);
		$lastfueltime = convertTimestamp(7, 12, 2012, 8, 0, 0);

		$sql_day = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp DESC LIMIT 1";
		$result_day = $connection->query($sql_day);
		$line_day = $result_day->fetch_array();
		$day_max = $line_day["energy"];
			
		$sql_day = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp ASC LIMIT 1";
		$result_day = $connection->query($sql_day);
		$line_day = $result_day->fetch_array();
		$day_min = $line_day["energy"];
		$energy_day = ($day_max - $day_min) / 1000;	
		$begin = convertTimestamp(1, $month, $year, 0, 0, 0);
       	$end   = convertTimestamp(getLastDayOfMonth($month, $year), 
				$month, $year, 23, 59, 59);
		
		$sql_month = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp DESC LIMIT 1";
		$result_month = $connection->query($sql_month);
		$line_month = $result_month->fetch_array();
		$month_max = $line_month["energy"];
			
		$sql_month = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp ASC LIMIT 1";
		$result_month = $connection->query($sql_month);
		$line_month = $result_month->fetch_array();
		$month_min = $line_month["energy"];
		$energy_month = ($month_max - $month_min) / 1000;	

		$begin = convertTimestamp(1, 1, $year, 0, 0, 0);
       	$end   = convertTimestamp(31, 12, $year, 23, 59, 59);

		$sql_year = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp DESC LIMIT 1";
		$result_year = $connection->query($sql_year);
		$line_year = $result_year->fetch_array();
		$year_max = $line_year["energy"];
			
		$sql_year = "SELECT energy FROM deltasole_wmz WHERE timestamp >= $begin AND timestamp <= $end and error=0 ORDER by timestamp ASC LIMIT 1";
		$result_year = $connection->query($sql_year);
		$line_year = $result_year->fetch_array();
		$year_min = $line_year["energy"];

		$energy_year = ($year_max - $year_min) / 1000;	

		echo "<p>Solar Mossner am: " . $line["dateformat"] . 
			" um " . $line["time"] . "</p>";
		echo "<table border=\"1\">\n";
  		echo "<tbody>\n";
    		echo "<tr>\n";
      		echo "<td>Kollektortemperatur: " . $line["temp_collector"] . "<br>Kollektortemperatur2: " . $line["temp_collector2"] . "</td>\n";
      		echo "<td>Temperatur Warmwasser unten: ". $line["temp_ww_bottom"] . "<br>Temperatur Warmwasser oben: " . $line["temp_ww_top"] . "</td>\n";
		echo "<td>Temperatur Speicher unten: ". $line["temp_buffer_bottom"] . "<br>Temperatur Speicher Mitte: " . $line["temp_buffer_mid"] . "<br>Temperatur Speicher oben: " . $line["temp_buffer_top"] .  "</td>\n";
		echo "</tr>\n";
    		echo "<tr>\n";
      		echo "<td>WMZ:<br>Vorlauf: ". $line["temp_wmz_vl"] . "<br>R&uuml;cklauf: " . $line["temp_wmz_rl"] . "</td>\n";
      		echo "<td>WMZ des Tages: " . $energy_day . "kw/h<br>" . 
			"WMZ des Monats: " . $energy_month . "kw/h<br>" .
			"WMZ des Jahres: " . $energy_year . "kw/h</td>\n";
      		echo "<td>Einstrahlung:" . $line["solarization"] . 
			" "."W/m2</td></tr><tr><td>\n";
    		
		if ($line["ww_relay"] > 0){ $ww_relay = "An";}
		else {$ww_relay = "Aus";}
		if ($line["buffer_relay"] > 0){ $buffer_relay = "An";}
		else {$buffer_relay = "Aus";}
		if ($line["rl_relay"] > 0){ $rl_relay = "An";}
		else {$rl_relay = "Aus";}
		if ($line["heatingpump"] > 0){ $heatingpump = "An";}
		else {$heatingpump = "Aus";}

		echo "Solarpumpe: " . $line["solarpump"] . "%";
		echo "</td><td>";
		echo "WW-Relais: " . $ww_relay . "<br>Puffer Relais: " . $buffer_relay;
		echo "</td><td>";
		echo "R&uuml;cklauf Relais: " . $rl_relay . " (Temp RL " . 
				$line["temp_rl"] . ")<br>Heizungspumpe: " . 
				$heatingpump; 
		echo "</td></tr>\n";
  		echo "</tbody>\n";
		echo "</table>\n";

		$sql = "SELECT *, DATE_FORMAT( `date`, '%d.%m.%Y') AS dateformat FROM vitocontrol ORDER BY  `vitocontrol`.`index` DESC LIMIT 1";
		$result = $connection->query($sql);
		$line = $result->fetch_array();
		
		$begin = convertTimestamp($day, $month, $year, 0, 0, 0);
        	$end   = convertTimestamp($day, $month, $year, 23, 59, 59);
		
		$sql_day = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp DESC LIMIT 1";
		$result_day = $connection->query($sql_day);
		$line_day = $result_day->fetch_array();
		$day_oil_max = $line_day["oil_consume"];
		$day_boiler_starts_max = $line_day["boiler_starts"];
		$day_boiler_hours_max = $line_day["boiler_hours1"];
			
		$sql_day = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp ASC LIMIT 1";
		$result_day = $connection->query($sql_day);
		$line_day = $result_day->fetch_array();
		$day_oil_min = $line_day["oil_consume"];
		$day_boiler_starts_min = $line_day["boiler_starts"];
		$day_boiler_hours_min = $line_day["boiler_hours1"];

		$oil_day = ($day_oil_max - $day_oil_min) / 1000;
		$boiler_starts_day = $day_boiler_starts_max -
			$day_boiler_starts_min;
		$boiler_hours_day = $day_boiler_hours_max - 
			$day_boiler_hours_min;
		if ($boiler_starts_day){
			$boiler_hours_average_day = 
				$boiler_hours_day /
				$boiler_starts_day;
		} else {
			$boiler_hours_average_day = 0;
		}

		$begin = convertTimestamp(1, $month, $year, 0, 0, 0);
       	$end   = convertTimestamp(getLastDayOfMonth($month, $year), 
			$month, $year, 23, 59, 59);
		
		$sql_month = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp DESC LIMIT 1";
		$result_month = $connection->query($sql_month);
		$line_month = $result_month->fetch_array();
		$month_oil_max = $line_month["oil_consume"];
		$month_boiler_starts_max = $line_month["boiler_starts"];
		$month_boiler_hours_max = $line_month["boiler_hours1"]; 
			
		$sql_month = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp ASC LIMIT 1";
		$result_month = $connection->query($sql_month);
		$line_month = $result_month->fetch_array();
		$month_oil_min = $line_month["oil_consume"];
		$month_boiler_starts_min = $line_month["boiler_starts"];
		$month_boiler_hours_min = $line_month["boiler_hours1"]; 

		$oil_month = ($month_oil_max - $month_oil_min) / 1000;
		$boiler_starts_month = $month_boiler_starts_max -
			$month_boiler_starts_min;
		$boiler_hours_month = $month_boiler_hours_max - 
			$month_boiler_hours_min;
		if ($boiler_starts_month){
			$boiler_hours_average_month = 
				$boiler_hours_month /
				$boiler_starts_month;
		} else {
			$boiler_hours_average_month = 0;
		}
		$begin = convertTimestamp(1, 1, $year, 0, 0, 0);
       	$end   = convertTimestamp(31, 12, $year, 23, 59, 59);

		$sql_year = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp DESC LIMIT 1";
		$result_year = $connection->query($sql_year);
		$line_year = $result_year->fetch_array();
		$oil_year_max = $line_year["oil_consume"];
		$boiler_starts_year_max = $line_year["boiler_starts"];
		$boiler_hours_year_max = $line_year["boiler_hours1"]; 

			
		$sql_year = "SELECT oil_consume,boiler_starts,boiler_hours1 FROM vitocontrol WHERE timestamp >= $begin AND timestamp <= $end ORDER by timestamp ASC LIMIT 1";
		$result_year = $connection->query($sql_year);
		$line_year = $result_year->fetch_array();
		$oil_year_min = $line_year["oil_consume"];
		$boiler_starts_year_min = $line_year["boiler_starts"];
		$boiler_hours_year_min = $line_year["boiler_hours1"]; 
		$oil_year = ($oil_year_max - $oil_year_min) / 1000;	
		$boiler_starts_year = $boiler_starts_year_max -
			$boiler_starts_year_min;
		$boiler_hours_year = $boiler_hours_year_max - 
			$boiler_hours_year_min;
		$boiler_hours_average_year = $boiler_hours_year / 
			$boiler_starts_year;

		/*lastfuel*/
		$sql_totalfuel = "SELECT SUM( oil_liter ) as totaloil FROM oil_fuel ORDER BY fuel_date DESC";
		$result_totalfuel = $connection->query($sql_totalfuel);
		$line_totalfuel = $result_totalfuel->fetch_array();
		$oil_total = $line_totalfuel["totaloil"];

		$sql_lastfuel = "SELECT vitocontrol.oil_consume,oil_fuel.fuel_date FROM vitocontrol JOIN oil_fuel on (vitocontrol.date=oil_fuel.fuel_date) ORDER by oil_fuel.fuel_date DESC, vitocontrol.time ASC LIMIT 1";
		$result_lastfuel = $connection->query($sql_lastfuel);
		$line_lastfuel = $result_lastfuel->fetch_array();
		$oil_lastfuel = $line_lastfuel["oil_consume"];
		$date_oil_last_fill = $line_lastfuel["fuel_date"];

		echo "<p>Heizung Mossner am: " . $line["dateformat"] . 
			" um " . $line["time"] . "</p>";
		echo "<table border=\"1\">\n";
  		echo "<tbody>\n";
    		echo "<tr>\n";
      		echo "<td>Kesselisttemperatur: " . $line["temp_boiler_is"] . 
				"<br>Kesselsolltemperatur: " . $line["temp_boiler_target"] . 
				"<br>Aussentemperatur: " . $line["temp_out"] . 
				" (ged. " . $line["temp_out_red"] . ")</td>\n";
      		echo "<td>Temperatur Vorlauf Ist M2: ". 
			$line["temp_vl_is_M2"] . 
			/*"<br>Temperatur Vorlauf Soll M2: " . 
			$line["temp_vl_target_M2"] . 
			echo "<br>Temperatur Vorlauf Soll M1: ". 
		  	$line["temp_vl_target_M1"]\n";*/
			"<br>Temperatur R&uuml;cklauf Ist M2: " . 
			$line["temp_rl_is_M2"] . "</td>\n";
		echo "<td>Abgastemperatur:  ". 
		  	$line["temp_exhaust"] . "</td>\n";
		echo "</tr>\n";
    	echo "<tr>\n";
		$total_oil_last_fill = $line["oil_consume"] / 1000 - ($oil_lastfuel / 1000);
		$oil_total = $oil_total + $total_oil_last_fill;
		echo "<td>Gesamt:<br>Brennerstarts:" . $line["boiler_starts"] .
			" <br>Kesselbetriebsstunden: ". 
			converthours($line["boiler_hours1"]) . 
/*			"<br>(Stufe2: " . 
			converthours($line["boiler_hours2"]) . ")"*/ 
			"<br>" .
			"&Ouml;lverbrauch: " .
			$oil_total . "l" .
			"<br>&Ouml;lverbrauch seit letzter Tankung: " . 
			$total_oil_last_fill . "l<br>am: ".  $date_oil_last_fill ."</td>\n";
      	echo "<td>&Ouml;lverbrauch des Tages: " . 
			$oil_day . "l<br>" . 
			"&Ouml;lverbrauch des Monats: " . 
			$oil_month . "l<br>" .
			"&Ouml;lverbrauch des Jahres: " . 
			$oil_year . "l</td>\n";
		if ($line["boiler_state"] > 0){ $boilerstate = "An";}
		else {$boilerstate = "Aus";}
    	echo "<td>Brenner: " . $boilerstate. 
			"<br>Brennerstarts Heute:" . $boiler_starts_day;
		echo "<br>Brennerstarts Monat:" . $boiler_starts_month;
		echo "<br>Brennerstarts Jahr:" . $boiler_starts_year . 
			"</td></tr><tr><td>\n";
		echo "Kesselbetriebsstunden Heute: ".
			converthours($boiler_hours_day);
		echo "<br>Kesselbetriebsstunden Monat: " . 
			converthours($boiler_hours_month);
		echo "<br>Kesselbetriebsstunden Jahr: " .
			converthours($boiler_hours_year);
		echo "</td><td>";
		echo "Durchschnittliche Kessellaufzeit: ";
		echo "<br>Tag: " . convertmins($boiler_hours_average_day);
		echo "<br>Monat: " . convertmins($boiler_hours_average_month);
		echo "<br>Jahr: " . convertmins($boiler_hours_average_year);
		echo "</td><td>";

		if ($line["pump_M1"] > 0){ $pumpM1 = "An";}
		else {$pumpM1 = "Aus";}
		if ($line["pump_ww"] > 0){ $pumpww = "An";}
		else {$pumpww = "Aus";}
		if ($line["pump_circ"] > 0){ $pumpcirc = "An";}
		else {$pumpcirc = "Aus";}
		if ($line["pump_M2"] > 0){ $pumpM2 = "An";}
		else {$pumpM2 = "Aus";}

		echo "Heizungspumpe M1: " . $pumpM1;
		echo "<br>Heizungspumpe M2: " . $pumpM2 . 
			"<br>Warmwasser Pumpe: " . $pumpww;
		echo "<br>Zirkulationspumpe: " . $pumpcirc;
		echo "</td></tr>\n";
  		echo "</tbody>\n";
		echo "</table>\n";
	 	
		$sql = "SELECT SUM( weight ) as sum_unten FROM `wood` WHERE who LIKE 'Unten'";
		$result = $connection->query($sql);
		$line = $result->fetch_array();
		$sum_unten = $line["sum_unten"];
		$sql = "SELECT SUM( weight ) as sum_oben FROM `wood` WHERE who LIKE 'Oben'";
		$result = $connection->query($sql);
		$line = $result->fetch_array();
		$sum_oben = $line["sum_oben"];
;
		echo "<br>Holz Mossner:<br><br>";
		echo "<table border=\"1\">\n";
  		echo "<tbody>\n";
    	echo "<tr>\n";
      	echo "<td>Holzverbrauch Oben Gesamt: " . (int) $sum_oben . 
			"kg<br>Holzverbrauch Unten Gesamt: " . (int) $sum_unten . 
			"kg</td>\n";
      	echo "</td></tr></tbody></table>\n";	

		$sql = "SELECT * FROM `temperatures_rooms` ORDER BY timestamp DESC LIMIT 1";
		$result = $connection->query($sql);
		$line = $result->fetch_array();
	
		echo "<br>Temperatur R&auml;ume am ";
		echo date("d.m.Y", strtotime($line["timestamp"]));
		echo " um ";
	   	echo date("G:i:s", strtotime($line["timestamp"]));
		echo ":<br><br>";
		echo "<table border=\"1\">\n";
  		echo "<tbody>\n";
    	echo "<tr>\n";
      	echo "<td>Abstellraum EG: " . $line["abstellraum_eg"] . "</td>\n";
		echo "<td>Arbeitszimmer Matthias: " . $line["arbeitszimmer"] . "</td>\n";
		echo "<td>Wohnzimmer_oben: " . $line["wohnzimmer_oben"] . "</td>\n";
		echo "<td>Badezimmer_unten: " . $line["badezimmer_unten"] . "</td>\n";
		echo "<td>Max Zimmer: " . $line["max_zimmer"] . "</td>\n";
		echo "<td>Kueche UG: " . $line["kueche_ug"] . "</td>\n";		
       	echo "</tr><tr>\n";
       	echo "<td>Schlafzimmer_unten: " . $line["schlafzimmer_unten"] . "</td>\n";
		echo "<td>Maja Zimmer: " . $line["maja_zimmer"] . "</td>\n";
		echo "<td>Wohnzimmer_unten: " . $line["wohnzimmer_unten"] . "</td>\n";
		echo "<td>Badezimmer oben: " . $line["badezimmer_oben"] . "</td>\n";
      	echo "<td>Arbeitszimmer Friedrich: " . $line["arbeitszimmer_friedrich"] . "</td>\n";
		echo "<td>Kueche EG: " . $line["kueche_eg"] . "</td>\n";
		echo "</tr></tbody></table>\n";	
  }
?>
</div>
  </body>
</html>
