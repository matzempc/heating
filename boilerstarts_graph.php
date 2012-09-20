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

		$begin = convertTimestamp($day_start, $month_start, $year_start, 
				0, 0, 0);
       	$end   = convertTimestamp($day_stop, $month_stop, $year_stop, 
				23, 59, 59);
	
		$sql = "SELECT `index` , `time` , `boiler_hours1`
				FROM `vitocontrol`
				WHERE `timestamp` >= $begin AND `timestamp` <= $end 
				GROUP BY `boiler_hours1`
				ORDER BY `boiler_hours1` ASC";
	    $result = mysql_query($sql);
	    $num = mysql_num_rows($result);
	    $texttickint = (integer) ($num % 40);
	    if ($num > 500){
	        $factor=(integer) ($num/500);
	    } else {
	        $factor = 1;
	    }
        if ($myrow = mysql_fetch_array($result)){
		    $start = $myrow["boiler_hours1"];
		    while($myrow = mysql_fetch_array($result))
		    {
		        if (!$starttime) $starttime = $myrow["time"];
		        if (!$step) $step = $myrow["index"];
		        if (!$boilerhours) $boilerhours = $myrow["boiler_hours1"];
		        if (++$step <= $myrow["index"]){
		        	$duration = $boilerhours - $start;
				    $boilerduration[] = $duration * 60;
				    $start = $boilerhours;
				    $step = $myrow["index"] + 1;
				    $starttime = $myrow["time"];
					$dates[] = $starttime;
  				}
           		$boilerhours = $myrow["boiler_hours1"];
        	}
        	if ($boilerhours != $start){
           		$duration = $boilerhours - $start;
			    $boilerduration[] = $duration *60;
				$dates[] = $starttime;
			}
	    }

	    $graph = new Graph(1000,500, "auto");
	    $graph->SetScale("textlin");
	    $graph->legend->SetAbsPos(5,5,'right','top');
	    $graph->SetMargin(40,170,20,150);
	    $texttickint = 1;
	    $graph->xaxis->SetTextTickInterval($texttickint);
	    $graph->xaxis->SetLabelAngle(90);
	    $graph->xaxis->SetPos('min');

        $graph->xaxis->SetTickLabels($dates);
        $p1 = new LinePlot($boilerduration);
        $p1->SetColor('green');
        $p1->SetLegend("Brennerlaufzeit");
		$graph->Add($p1);
		$graph->Stroke();

	}
}
?>
</div>
  </body>
</html>
