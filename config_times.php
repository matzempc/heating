<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
<script language="javascript" src="md5.js"></script>
<script language="javascript">
<!--
  function doChallengeResponse() {
    document.configtimes.code.value = MD5(document.configtimes.code.value);
  }
// -->
</script>
<link rel="stylesheet" href="heating.css">
<title>Heizung</title>
  </head>
  <body>
<div align="center">
<?php
$code = 4710;

$days_array = array("Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag");

$command_array = array(
	array("'getTimerM1Mo'","'getTimerM1Di'","'getTimerM1Mi'","'getTimerM1Do'","'getTimerM1Fr'","'getTimerM1Sa'","'getTimerM1So'"),
	array("'getTimerM2Mo'","'getTimerM2Di'","'getTimerM2Mi'","'getTimerM2Do'","'getTimerM2Fr'","'getTimerM2Sa'","'getTimerM2So'"),
	array("'getTimerWWMo'","'getTimerWWDi'","'getTimerWWMi'","'getTimerWWDo'","'getTimerWWFr'","'getTimerWWSa'","'getTimerWWSo'")
);

$setcommand_array = array(
	array("setTimerM1Mo","setTimerM1Di","setTimerM1Mi","setTimerM1Do","setTimerM1Fr","setTimerM1Sa","setTimerM1So"),
	array("setTimerM2Mo","setTimerM2Di","setTimerM2Mi","setTimerM2Do","setTimerM2Fr","setTimerM2Sa","setTimerM2So"),
	array("setTimerWWMo","setTimerWWDi","setTimerWWMi","setTimerWWDo","setTimerWWFr","setTimerWWSa","setTimerWWSo")
);

$times_array = array(
	array("mo11","mo12","mo21","mo22","mo31","mo32","mo41","mo42"),
	array("di11","di12","di21","di22","di31","di32","di41","di42"),
	array("mi11","mi12","mi21","mi22","mi31","mi32","mi41","mi42"),
	array("do11","do12","do21","do22","do31","do32","do41","do42"),
	array("fr11","fr12","fr21","fr22","fr31","fr32","fr41","fr42"),
	array("sa11","sa12","sa21","sa22","sa31","sa32","sa41","sa42"),
	array("so11","so12","so21","so22","so31","so32","so41","so42")						
);

$type = $_GET["type"]; /* M1, M2 or WW => 0,1 or 2*/

for ($n = 0; $n < 7; $n++){
	$commands .= $command_array[$type][$n];
	if ($n < 6) $commands .= ",";	
}

/*first fetch values*/
exec("./vclient -h 127.0.0.1:3003 -c " . $commands, $output , $retval);

if ($retval != 0) echo "VCONTROL BUSY AT THE MOMENT!<br>\n";
if (count($output) < count($command_array[$type])*5){ /*5=commandstring+4*times*/
 echo "VCONTROL answer is too short!<br>\n";
 exit;
}
//var_dump($output);
/*change values if needed*/
if (strcmp(trim(substr($output[1], 5, 5)), "merM1") == 0){
  $i = 2;
} else {
  $i = 1;
}
if ($_GET["submitted"] && $_GET["code"] == md5($code)){
	echo "Configured<br>\n";
	for ($day = 0; $day < 7; $day++){
		$changed = FALSE;
		for ($j = 0; $j < 8; $j++){
		  if (strcmp(trim(substr($output[$i], 5, 5)), $_GET[$times_array[$day][$j++]]) ||
		    strcmp(trim(substr($output[$i], 16, 5)) , $_GET[$times_array[$day][$j]])){
		    $changed = TRUE;
		  }
		  $i++;
		}
		if ($changed == TRUE){
		  for ($j = 0; $j < 8; $j++){
			$timename = $times_array[$day][$j];
			$timevalues .= strcmp(trim($_GET[$timename]), "--") ? $_GET[$timename] : "";
			/*place here functionality to change output[""] to new value*/
			if ($j < 7){
			  $timevalues .= " ";
			}
		  }
		  trim($timevalues);
		  //printf("./vclient -h 127.0.0.1:3003 -c '" . $setcommand_array[$type][$day] . " " . $timevalues . "'");
		  exec("./vclient -h 127.0.0.1:3003 -c '" . $setcommand_array[$type][$day] . " " . $timevalues . "'", $write_output , $retval);
		  $timevalues = "";
		}
		$i++;
	}
}

echo "<form name=\"configtimes\">\n";
echo "<br>";
echo "<table><tr><td>";
$i = 1;
for ($day = 0; $day < 7; $day++)
{
	echo $days_array[$day] . ": ";
	echo "<table><tr><td>\n";
	for ($j = 0; $j < 8; $j++){
		if (($j % 2) == 0){
			$timer = trim(substr($output[$i], 5, 5));
			if ($i == 1 && strcmp($timer, "merM1") == 0){
 				$i++;
 				$timer = trim(substr($output[$i], 5, 5));
			}
		} else {
			$timer = trim(substr($output[$i], 16, 5));
		}
		if (($j % 2) == 0){
			echo (($j/2)+1) . ": ";
		}
		echo "<input type=\"text\" name=\"" . $times_array[$day][$j] . "\" id=\"" . $times_array[$day][$j] . "\" value=\"". $timer . "\" size=\"5\">\n";
		if (($j % 2) == 1){
		  echo "</td></tr><tr><td>\n";
		  $i++;
		}
	}
	$i++;
	echo "</td></tr></table>\n";
	if (($day % 4) == 3){
	  echo "</td></tr><tr><td>\n";
	} else {
	  echo "</td><td>\n";
	}
}
echo "</td></tr></table><br><br>";
echo "<input type=\"password\" name=\"code\" size=\"4\"><br>\n";
echo "<input type=\"hidden\" name=\"type\" value=\"$type\">\n";
echo "<input type=\"hidden\" name=\"submitted\" value=\"1\">\n";
echo "<input onClick=\"doChallengeResponse();\" type=\"submit\" value=\"OK\">\n";
echo "<form>\n";

?>
</div>
  </body>
</html>
