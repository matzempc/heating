<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
<link rel="stylesheet" href="heating.css">
<title>Heizung</title>
  </head>
  <body>
<div align="center">
<?php
   
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

function convertmins($dec_time){
        if (substr_count($dec_time, ",") > 0)
        $dec_time = str_replace(",", ".", $dec_time);
    $dec_time = floatval($dec_time);
    #strip hours
    $in_hours = abs($dec_time);
    $in_seconds = $in_hours*3600;
    $in_seconds = $in_seconds + (3600*($dec_time-$in_hours));
    #convert readable
    $minutes = floor($in_seconds / 60);// % 60; we don't show hours therefore minutes are also displayed above 60
    $seconds = $in_seconds % 60;
    if($minutes < 10) $minutesstring = "0" . $minutes;
    else $minutesstring = (string) $minutes;
    if($seconds < 10) $secondsstring = "0" . $seconds;
    else $secondsstring = (string) $seconds;
    $string = sprintf("%s:%s (mins)",$minutesstring,$secondsstring);
    return (string)$string;
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

	$begin = convertTimestamp($day_start, $month_start, $year_start, 
			0, 0, 0);
     	$end   = convertTimestamp($day_stop, $month_stop, $year_stop, 
			23, 59, 59);

	$sql = "SELECT `index` , `time` , `boiler_hours1`
			FROM `vitocontrol`
			WHERE `timestamp` >= $begin AND `timestamp` <= $end 
			GROUP BY `boiler_hours1`
			ORDER BY `boiler_hours1` ASC LIMIT 1";

	$result = $connection->query($sql);
	if ($result && $myrow = $result->fetch_array()){
		$boilerhours_min = $myrow["boiler_hours1"];
	}
	$sql = "SELECT `index` , `time` , `boiler_hours1`
			FROM `vitocontrol`
			WHERE `timestamp` >= $begin AND `timestamp` <= $end 
			GROUP BY `boiler_hours1`
			ORDER BY `boiler_hours1` DESC LIMIT 1";

	$result = $connection->query($sql);
	if ($result && $myrow = $result->fetch_array()){
		if ($boilerhours_min == $myrow["boiler_hours1"]){
			echo "No Boilerstarts in this time!<br>";
		   	exit(0);	
		}	
	}
	echo "<img src=\"boilerstarts_graph.php?daystart=" .
 			 $_GET["daystart"] . "&daystop=" . $_GET["daystop"] . 
		 "&monthstart=" .
		$_GET["monthstart"] . "&monthstop=" . $_GET["monthstop"] . 
		"&yearstart=" .
		$_GET["yearstart"] ."&yearstop=" . $_GET["yearstop"] . 
		"\"<br><hr><br>";


	$sql = "SELECT `index` , `time` , `boiler_hours1`
			FROM `vitocontrol`
			WHERE `timestamp` >= $begin AND `timestamp` <= $end 
			GROUP BY `boiler_hours1`
			ORDER BY `boiler_hours1` ASC";
	
	echo "<table border=\"1\">\n";
		echo "<tbody>\n";
  	echo "<tr>\n";

	$result = $connection->query($sql);
	$i = 0;
	$j = 1;
	if ($myrow = $result->fetch_array()){
		$start = $myrow["boiler_hours1"];
		while($myrow = $result->fetch_array())
		{
		 	/*	
			echo $myrow["index"] . " " . $myrow["boiler_hours1"] . " " . 
				$myrow["time"] . "<br>";*/
			if (!$starttime) $starttime = $myrow["time"];
			if (!$step) $step = $myrow["index"];
			if (!$boilerhours) $boilerhours = $myrow["boiler_hours1"];
			if (++$step <= $myrow["index"]){
				if ($i++ >= 5){ 
					$i = 1;
					echo "</tr><tr>";
				}
		 		$duration = $boilerhours - $start;
				echo "<td>";
				//echo "$start -> $boilerhours ($duration)<br>";
				echo $j++ . ".Start ($starttime):<br>" . 
					 convertmins($duration) . "</td>\n";
				$start = $boilerhours;
				$step = $myrow["index"] + 1;
				$starttime = $myrow["time"];

			}
			$boilerhours = $myrow["boiler_hours1"];

		}
		if ($boilerhours != $start){
			if ($i > 5){ 
				echo "</tr><tr>";
			}
			$duration = $boilerhours - $start;
			echo "<td>";
			//echo "$start -> $boilerhours ($duration)<br>";
			echo $j . ".Start ($starttime):<br>" . 
			   convertmins($duration) . "</td>\n";
		}
	}
	echo "</tr>\n";
		echo "</tbody>\n";
	echo "</table>\n";
}
?>
</div>
  </body>
</html>
